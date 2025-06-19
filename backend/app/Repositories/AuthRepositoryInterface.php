<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
    public function login(array $credentials);
    public function logout(Request $request);
} 