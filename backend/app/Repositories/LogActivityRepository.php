<?php

namespace App\Repositories;

use App\Models\LogActivitys;
use Illuminate\Support\Facades\Auth;

class LogActivityRepository implements LogActivityRepositoryInterface
{
    public function store(array $data = [])
    {
        $user = Auth::user();

        return LogActivitys::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'user_id' => $user->user_id,
        ]);
    }
}