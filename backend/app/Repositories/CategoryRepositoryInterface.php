<?php 

namespace App\Repositories;

use Illuminate\Http\Request;

interface CategoryRepositoryInterface 
{
    public function store(Request $request);
    public function getById($id);
    public function get();
    public function update($id, Request $request);
    public function delete($id);
}