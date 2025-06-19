<?php

namespace App\Http\Controllers;

use App\Mail\ReimbursementSubmitted;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\LogActivityRepositoryInterface;
use App\Repositories\ReimbursementRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReimbursementController extends Controller
{
    protected $reimbursementRepo;
    protected $categoryRepo;
    protected $logActivityRepo;
    protected $userRepository;

    public function __construct(
        ReimbursementRepositoryInterface $reimbursementRepo, 
        CategoryRepositoryInterface $categoryRepo,
        LogActivityRepositoryInterface $logActivityRepo,
        UserRepositoryInterface $userRepository
        )
    {
        $this->reimbursementRepo = $reimbursementRepo;
        $this->categoryRepo = $categoryRepo;
        $this->logActivityRepo = $logActivityRepo;
        $this->userRepository = $userRepository;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,category_id',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        
        $user = Auth::user();
        if ($user->role_id != '3') {
            return response()->json([
                'message' => 'you don not have access'
            ], 403);
        }

        try {
            $now = Carbon::now();
            $category = $this->categoryRepo->getById($request->category_id);
            $amount_reimbursement = $this->reimbursementRepo->amountCategory($request->category_id, $now->year, $now->month, ['approve']);
            $balance = $category->limit_per_month - $amount_reimbursement;
            if ($request->amount > $balance) {
                return response()->json([
                    'message' => 'This month balance is not enough'
                ], 422);
            }

            $reimbursement = $this->reimbursementRepo->store($request);
            if ($reimbursement) {
                $this->logActivityRepo->store([
                    'name' => 'Create Reimbursement',
                    'description' => 'status reimbursement pending'
                ]);

                $managers = $this->userRepository->getUserByRoleId(1);
                foreach ($managers as $manager) {
                    //Send Email with queue
                    Mail::to($manager->email)->queue(new ReimbursementSubmitted($reimbursement, $user));
                }

                return response()->json([
                    'message' => 'Reimbursement saved successfully.',
                    'data' => $reimbursement,
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Failed to save reimbursement data.',
                    'data' => []
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while saving the reimbursement.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function get(Request $request)
    {
        $user = Auth::user();
        if ($user->role_id == 1) {
             $reimbursement = $this->reimbursementRepo->get($request);
        } else if ($user->role_id == 2) {
            $reimbursement = $this->reimbursementRepo->getAllsoftDelete($request);
        } else {
            $reimbursement = $this->reimbursementRepo->getByUserId($user->user_id, $request);
        }
       

        if ($reimbursement->isEmpty()) {
            return response()->json([
                'message' => 'No reimbursement data found.',
                'data' => []
            ], 200);
        }

        return response()->json([
            'message' => 'Reimbursement retrieved successfully.',
            'data' => $reimbursement
        ], 200);
    }

    public function show($id)
    {
        $reimbursement = $this->reimbursementRepo->getById($id);

        if (!$reimbursement) {
            return response()->json([
                'message' => 'Reimbursement not found.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Reimbursement retrieved successfully.',
            'data' => $reimbursement
        ]);
    }

    public function get_by_user_id($id, Request $request)
    {
        $reimbursement = $this->reimbursementRepo->getByUserId($id, $request);

        if (!$reimbursement) {
            return response()->json([
                'message' => 'Reimbursement not found.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Reimbursement retrieved successfully.',
            'data' => $reimbursement
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'sometimes|required|numeric|min:0',
            'category_id' => 'sometimes|required|exists:categories,category_id',
            'file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        if ($user->role_id != '3') {
            return response()->json([
                'message' => 'you don not have access'
            ], 403);
        }

        $reimbursement = $this->reimbursementRepo->update($id, $request);

        if (!$reimbursement) {
            return response()->json([
                'message' => 'Reimbursement not found or failed to update.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Reimbursement updated successfully.',
            'data' => $reimbursement
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->role_id != '3') {
            return response()->json([
                'message' => 'you don not have access'
            ], 403);
        } 

        $deleted = $this->reimbursementRepo->delete($id);

        if (!$deleted) {
            return response()->json([
                'message' => 'Reimbursement not found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Reimbursement deleted successfully.'
        ], 200);
    }

    public function update_status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approve,reject',
        ]);

        if ($request->status == 'approve') {
            $now = Carbon::now();
            $reimbursement = $this->reimbursementRepo->getById($id);
            if (!$reimbursement) {
                return response()->json(['message' => 'Reimbursement not found'], 404);
            }

            $category = $this->categoryRepo->getById($reimbursement->category_id);
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            // Ambil total amount dari kategori tersebut bulan ini
            $amountUsed = $this->reimbursementRepo->amountCategory($reimbursement->category_id, $now->year, $now->month, ['approve']);

            // Hitung sisa saldo bulan ini
            $balance = $category->limit_per_month - $amountUsed;

            if ($reimbursement->amount > $balance) {
                return response()->json([
                    'message' => 'This month balance is not enough'
                ], 422);
            }
        }
        $user = Auth::user();
        if ($user->role_id == '1') {
            $update = $this->reimbursementRepo->updateStatus($id, $request);
            $this->logActivityRepo->store([
                'name' => 'Approvement',
                'description' => 'Reimbursement di ' . $request->status
            ]);

            if (!$update) {
                return response()->json([
                    'message' => 'Reimbursement not found or failed to update.',
                ], 404);
            }

            return response()->json([
                'message' => 'Reimbursement updated successfully.',
            ]);
        } else {
            return response()->json([
                'message' => 'You are not manager.',
            ], 403);
        }

    }
}
