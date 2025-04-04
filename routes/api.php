<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Регистрация пользователя
Route::post('/register', [AuthController::class, 'register']);
// Авторизация
Route::post('/login', [AuthController::class, 'login']);
// Выход
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
