<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

// Регистрация пользователя
Route::post('/register', [AuthController::class, 'register']);
// Авторизация
Route::post('/login', [AuthController::class, 'login']);
// Выход
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

// Товары
Route::get('/product', [ProductController::class, 'index']);
Route::post('/product', [ProductController::class, 'store'])->middleware('auth:api');
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::patch('/product/{id}', [ProductController::class, 'update'])->middleware('auth:api');
Route::delete('/product/{id}', [ProductController::class, 'destroy'])->middleware('auth:api');
