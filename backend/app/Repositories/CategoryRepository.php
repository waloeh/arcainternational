<?php

namespace App\Repositories;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function store(Request $request)
    {
        return Categories::create([
            'name' => $request->name,
            'limit_per_month' => $request->limit_per_month
        ]);
    }

    public function getById($id)
    {
        $data = Categories::find($id);
        return $data;
    }

    public function get()
    {
        return Categories::latest()->get();
    }

    public function update($id, Request $request)
    {
        $category = Categories::find($id);

        if (!$category) {
            return null;
        }

        $category->name = $request->name ?? $category->name;
        $category->limit_per_month = $request->limit_per_month ?? $category->limit_per_month;

        $category->save();

        return $category;
    }

    public function delete($id)
    {
        $category = Categories::find($id);

        if (!$category) {
            return false;
        }

        return $category->delete();
    }
}