<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReimbursementController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::prefix('/reimbursement')->group(function () {
        Route::post('/', [ReimbursementController::class, 'store']);  
        Route::get('/', [ReimbursementController::class, 'get']);       
        Route::get('/{id}', [ReimbursementController::class, 'show']);
        Route::get('/user/{user_id}', [ReimbursementController::class, 'get_by_user_id']);
        Route::put('/{id}', [ReimbursementController::class, 'update']); 
        Route::delete('/{id}', [ReimbursementController::class, 'destroy']);
        Route::patch('/{id}', [ReimbursementController::class, 'update_status']);
    });
    Route::prefix('/category')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);  
        Route::get('/', [CategoryController::class, 'get']);       
        Route::get('/{id}', [CategoryController::class, 'show']);    
        Route::put('/{id}', [CategoryController::class, 'update']); 
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });
});


