<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\ViewFilterController;

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

// Профиль
Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth:api');
Route::patch('/profile', [ProfileController::class, 'update'])->middleware('auth:api');

// Товары по фильтрам
Route::get('/country/{id}', [FilterController::class, 'countryIndex']);
Route::get('/category/{id}', [FilterController::class, 'categoryIndex']);
Route::get('/category/{categoryId}/country/{countryId}', [FilterController::class, 'categoryAndCountryIndex']);

// Вывод категорий и стран
Route::get('/country', [ViewFilterController::class, 'allCountryIndex']);
Route::get('/category', [ViewFilterController::class, 'allCategoryIndex']);


