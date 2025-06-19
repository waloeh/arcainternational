<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getUserByRoleId($id)
    {
        return User::where('role_id', $id)->get();
    }
}