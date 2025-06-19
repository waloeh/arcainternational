<?php

namespace App\Repositories;

interface UserRepositoryInterface 
{
    public function getUserByRoleId($id);
}