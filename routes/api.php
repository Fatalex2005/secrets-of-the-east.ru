<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\ViewFilterController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\СartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;


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
Route::get('/products/filter', [FilterController::class, 'filterProducts']);
Route::get('/products/search', [FilterController::class, 'search']);

// Вывод категорий и стран
Route::get('/country', [ViewFilterController::class, 'allCountryIndex']);
Route::get('/category', [ViewFilterController::class, 'allCategoryIndex']);

// Отзывы
Route::get('/product/{id}/review', [ReviewController::class, 'index']);
Route::post('/product/{id}/review', [ReviewController::class, 'store'])->middleware('auth:api');
Route::delete('/product/{productId}/review/{reviewId}', [ReviewController::class, 'destroy'])->middleware('auth:api');

// Пункты выдачи
Route::get('/point', [PointController::class, 'index']);
Route::post('/point', [PointController::class, 'store'])->middleware('auth:api');
Route::patch('/point/{id}', [PointController::class, 'update'])->middleware('auth:api');
Route::delete('/point/{id}', [PointController::class, 'destroy'])->middleware('auth:api');

// Корзина
Route::get('/cart', [СartController::class, 'show'])->middleware('auth:api');
Route::post('/cart/product/{productColorSizeId}', [СartController::class, 'addProduct'])->middleware('auth:api');
Route::patch('/cart/product/{productColorSizeId}', [СartController::class, 'updateProduct'])->middleware('auth:api');
Route::delete('/cart/product/{productColorSizeId}', [СartController::class, 'removeProduct'])->middleware('auth:api');

// Заказы
Route::get('/order', [OrderController::class, 'index'])->middleware('auth:api');
Route::post('/order', [OrderController::class, 'store'])->middleware('auth:api');
Route::get('/order/{id}', [OrderController::class, 'show'])->middleware('auth:api');
Route::patch('/order/{id}', [OrderController::class, 'update'])->middleware('auth:api');
Route::patch('/order/cancelled/{id}', [OrderController::class, 'destroy'])->middleware('auth:api');

// Оплата
Route::post('/create-checkout-session', [StripeController::class, 'createCheckoutSession']);
Route::get('/payment-success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/payment-cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');

// Создание менеджера
Route::post('/create-manager', [UserController::class, 'createManager'])->middleware('auth:api');
