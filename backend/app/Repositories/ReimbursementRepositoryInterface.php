<?php
namespace App\Repositories;

use Illuminate\Http\Request;

interface ReimbursementRepositoryInterface
{
    public function store(Request $request);
    public function getById($id);
    public function getByUserId($id, Request $request);
    public function get(Request $request);
    public function getAllsoftDelete(Request $request); //soft delete aanf all
    public function update($id, Request $request);
    public function delete($id);
    public function updateStatus($id, Request $request);
    public function amountCategory($id_category, $year, $month, array $status = []); //amount per category per month
}