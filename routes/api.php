<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Регистрация пользователя
Route::post('/register', [AuthController::class, 'register'])->middleware('auth:api');
// Авторизация
Route::post('/login', [AuthController::class, 'login']);
// Выход
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
