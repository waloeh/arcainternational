<?php

namespace App\Repositories;

use App\Models\Reimbursements;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Stringable;

class ReimbursementRepository implements ReimbursementRepositoryInterface
{
    public function store(Request $request) 
    {
        $filePath = $request->file('file')->store('uploads', 'public');

        $user = Auth::user();

        return Reimbursements::create([
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'file_name' => $filePath,
            'user_id' => $user->user_id,
            'submited_at' => now()
        ]);
    }

    public function getById($id)
    {
        $data = Reimbursements::select(
                'reimbursements.*',
                'categories.name as category_name',
                'users.username as user_name'
            )
            ->join('categories', 'categories.category_id', '=', 'reimbursements.category_id')
            ->join('users', 'users.user_id', '=', 'reimbursements.user_id')
            ->where('reimbursements.id_reimburse', $id)
            ->first();

        if ($data) {
            $data->file_url = asset('storage/' . $data->file_name);
        }

        return $data;
    }

    public function getByUserId($id, Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $query = Reimbursements::select(
                'reimbursements.*',
                'categories.name as category_name',
                'users.username as user_name'
            )
            ->join('categories', 'categories.category_id', '=', 'reimbursements.category_id')
            ->join('users', 'users.user_id', '=', 'reimbursements.user_id')
            ->where('reimbursements.user_id', $id)
            ->latest('reimbursements.created_at');

        //jika ada search
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('reimbursements.title', 'like', "%{$search}%")
                ->orWhere('categories.name',   'like', "%{$search}%")
                ->orWhere('users.username',    'like', "%{$search}%");
            });
        }

        $data = $query->paginate(10);

        $data->getCollection()->transform(function ($item) {
            $item->file_url = asset('storage/' . $item->file_name);
            return $item;
        });

        return $data;
    }

    public function get(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $query = Reimbursements::select(
                'reimbursements.*',
                'categories.name as category_name',
                'users.username as user_name'
            )
            ->join('categories', 'categories.category_id', '=', 'reimbursements.category_id')
            ->join('users', 'users.user_id', '=', 'reimbursements.user_id')
            ->latest('reimbursements.created_at');

        //jika ada search
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('reimbursements.title', 'like', "%{$search}%")
                ->orWhere('categories.name',   'like', "%{$search}%")
                ->orWhere('users.username',    'like', "%{$search}%");
            });
        }

        $data = $query->paginate(10);

        $data->getCollection()->transform(function ($item) {
            $item->file_url = asset('storage/' . $item->file_name);
            return $item;
        });

        return $data;
    }

        public function getAllsoftDelete(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $query = Reimbursements::withTrashed()->select(
                'reimbursements.*',
                'categories.name as category_name',
                'users.username as user_name'
            )
            ->join('categories', 'categories.category_id', '=', 'reimbursements.category_id')
            ->join('users', 'users.user_id', '=', 'reimbursements.user_id')
            ->latest('reimbursements.created_at');

        //jika ada search
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('reimbursements.title', 'like', "%{$search}%")
                ->orWhere('categories.name',   'like', "%{$search}%")
                ->orWhere('users.username',    'like', "%{$search}%");
            });
        }

        $data = $query->paginate(10);

        $data->getCollection()->transform(function ($item) {
            $item->file_url = asset('storage/' . $item->file_name);
            return $item;
        });

        return $data;
    }

    public function update($id, Request $request)
    {
        $reimbursement = Reimbursements::find($id);

        if (!$reimbursement) {
            return null;
        }

        $reimbursement->title = $request->title ?? $reimbursement->title;
        $reimbursement->description = $request->description ?? $reimbursement->description;
        $reimbursement->amount = $request->amount ?? $reimbursement->amount;
        $reimbursement->category_id = $request->category_id ?? $reimbursement->category_id;

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($reimbursement->file_name) {
                Storage::disk('public')->delete($reimbursement->file_name);
            }

            $reimbursement->file_name = $request->file('file')->store('uploads', 'public');
        }

        $reimbursement->save();

        return $reimbursement;
    }

    public function delete($id)
    {
        $reimbursement = Reimbursements::find($id);

        if (!$reimbursement) {
            return false;
        }

        // Hapus file jika ada
        if ($reimbursement->file_name) {
            Storage::disk('public')->delete($reimbursement->file_name);
        }

        return $reimbursement->delete();
    }

    public function updateStatus($id, Request $request)
    {
        $reimbursement = Reimbursements::find($id);

        if (!$reimbursement) {
            return null;
        }

        $reimbursement->status = $request->status;
        $reimbursement->approved_at = now();
        $reimbursement->save();
        
        return $reimbursement;
    }

    public function amountCategory($id_category, $year, $month, array $status = ['approve'])
    {
        $amount = Reimbursements::where('category_id', $id_category)
            ->whereIn('status', $status)
            ->whereYear('submited_at', $year)
            ->whereMonth('submited_at', $month)
            ->sum('amount');

        return $amount;
    }
}