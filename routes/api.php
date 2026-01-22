<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InscirptionController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/salles', [SalleController::class , 'index']);
    Route::post('/salles', [SalleController::class , 'store']);
    Route::get('/salles/{salle}', [SalleController::class , 'show']);
    Route::post('/salles/{salle}', [SalleController::class , 'update']);
    Route::delete('/salles/{salle}', [SalleController::class , 'destroy']);
    Route::get('/cours', [CourController::class , 'index']);
    Route::post('/cours', [CourController::class , 'store']);
    Route::get('/cours/{cour}', [CourController::class , 'show']);
    Route::post('/cours/{cour}', [CourController::class , 'update']);
    Route::delete('/cours/{cour}', [CourController::class , 'destroy']);

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
    Route::get('salle/{salle}',[SalleController::class , 'show']);
    Route::get('salle',[SalleController::class , 'index']);
    Route::get('cour/{cour}',[CourController::class , 'show']);
    Route::get('cour',[CourController::class , 'index']);
    Route::post('/inscription', [InscirptionController::class , 'store']);
    Route::get('/inscription/{client_id}', [InscirptionController::class , 'index']);
    Route::put('/inscription/{inscirption}', [InscirptionController::class , 'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
});