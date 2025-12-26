<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InscirptionController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('salles', SalleController::class);
    Route::apiResource('cours', CourController::class);
    Route::apiResource('users', UserController::class);
    Route::get('/inscriptions', [InscirptionController::class , 'index']);
    Route::put('/inscriptions/{inscirption}', [InscirptionController::class , 'update']);
    Route::delete('/inscriptions/{inscirption}', [InscirptionController::class , 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:coach'])->group(function () {
    Route::get('/coach/cours/{coach_id}', [CourController::class , 'index']);
    Route::get('/coach/inscriptions', [InscirptionController::class , 'indexByCoach']);
});

Route::middleware(['auth:sanctum', 'role:client'])->group(function () {
    Route::get('salle',[SalleController::class , 'index']);
    Route::get('cour',[CourController::class , 'index']);
    Route::post('/inscription', [InscirptionController::class , 'store']);
    Route::get('/inscription/{client_id}', [InscirptionController::class , 'index']);
    Route::put('/inscription/{inscirption}', [InscirptionController::class , 'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});