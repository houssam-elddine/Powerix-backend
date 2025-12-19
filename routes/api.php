<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AbonnementController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('abonnements', AbonnementController::class);
    Route::apiResource('salles', SalleController::class);
    Route::apiResource('cours', CourController::class);
    Route::apiResource('users', UserController::class);

});

Route::middleware(['auth:sanctum', 'role:coach'])->group(function () {
    
});

Route::middleware(['auth:sanctum', 'role:client'])->group(function () {
    
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});