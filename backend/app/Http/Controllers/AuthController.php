<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        return $this->authRepository->login($credentials);
    }

    public function logout(Request $request)
    {
        return $this->authRepository->logout($request);
    }
}
