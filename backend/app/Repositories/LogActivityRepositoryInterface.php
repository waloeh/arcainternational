<?php
namespace App\Repositories;


interface LogActivityRepositoryInterface 
{
    public function store(array $data = []);
}