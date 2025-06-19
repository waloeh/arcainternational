<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepository;
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    
    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'limit_per_month' => 'required|numeric',
        ]);

        $category = $this->categoryRepository->store($request);

        if (!$category) {
            return response()->json([
                'message' => 'Failed to save category data.',
                'data' => []
            ], 500);
        }

        return response()->json([
            'message' => 'Category saved successfully.',
            'data' => $category
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'limit_per_month' => 'required|numeric',
        ]);

        $category = $this->categoryRepository->update($id, $request);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Category updated successfully',
            'date' => $category
        ]);
    }

    public function get()
    {
        $category = $this->categoryRepository->get();
        if ($category->isEMpty()) {
            return response()->json([
                'message' => 'No categories data found.',
                'data' => []
            ], 400);
        }

        return response()->json([
            'message' => 'categories retrieved successfully.',
            'data' => $category
        ], 200);
    }

    public function show($id)
    {
        $category = $this->categoryRepository->getById($id);
        if (!$category) {
            return response()->json([
                'message' => 'Data not found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Data Category',
            'data' => $category
        ]);
    }

    public function destroy($id) 
    {
        $category = $this->categoryRepository->delete($id);

        if (!$category) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}
