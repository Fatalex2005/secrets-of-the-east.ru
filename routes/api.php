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
use App\Http\Controllers\UserController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\CategoryController;


// Регистрация пользователя
Route::post('/register', [AuthController::class, 'register']);
// Авторизация
Route::post('/login', [AuthController::class, 'login']);
// Выход
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

// Товары
Route::get('/product', [ProductController::class, 'index']);   // Просмотр всех товаров
Route::post('/product', [ProductController::class, 'store'])->middleware('auth:api');   // Создание товара
Route::get('/product/{id}', [ProductController::class, 'show']);   // Просмотр конкретного товара
Route::patch('/product/{id}', [ProductController::class, 'update'])->middleware('auth:api');   // Обновление товара
Route::delete('/product/{id}', [ProductController::class, 'destroy'])->middleware('auth:api');   // Удаление товара

// Профиль
Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth:api');   // Просмотр профиля
Route::patch('/profile', [ProfileController::class, 'update'])->middleware('auth:api');   // Обновление своего профиля

// Товары по фильтрам
Route::get('/products/filter', [FilterController::class, 'filterProducts']);   // Фильтр по категории, стране, цене и полу
Route::get('/products/search', [FilterController::class, 'search']);   // Поиск по тексту

// Вывод категорий и стран
Route::get('/country', [ViewFilterController::class, 'allCountryIndex']);
Route::get('/category', [ViewFilterController::class, 'allCategoryIndex']);

// Создание категории
Route::post('/category', [CategoryController::class, 'store'])->middleware('auth:api');

// Отзывы
Route::get('/product/{id}/review', [ReviewController::class, 'index']);   // Просмотр отзывов по товару
Route::post('/product/{id}/review', [ReviewController::class, 'store'])->middleware('auth:api');   // Создание отзыва
Route::delete('/product/{productId}/review/{reviewId}', [ReviewController::class, 'destroy'])->middleware('auth:api');   // Удаление отзыва

// Пункты выдачи
Route::get('/point', [PointController::class, 'index']);   // Просмотр всех пунктов выдачи
Route::post('/point', [PointController::class, 'store'])->middleware('auth:api');   // Создание пункта выдачи
Route::patch('/point/{id}', [PointController::class, 'update'])->middleware('auth:api');   // Редактирование пункта выдачи
Route::delete('/point/{id}', [PointController::class, 'destroy'])->middleware('auth:api');   // Удаление пункта выдачи

// Корзина
Route::get('/cart', [СartController::class, 'show'])->middleware('auth:api');   // Просмотр товаров всех товаров в своей корзине
Route::post('/cart/product/{productColorSizeId}', [СartController::class, 'addProduct'])->middleware('auth:api');   // Добаление товара в корзину
Route::patch('/cart/product/{productColorSizeId}', [СartController::class, 'updateProduct'])->middleware('auth:api');   // Обновление количества товара в корзине
Route::delete('/cart/product/{productColorSizeId}', [СartController::class, 'removeProduct'])->middleware('auth:api');   // Удаление товара из корзины

// Заказы
Route::get('/order', [OrderController::class, 'index'])->middleware('auth:api');   // Просмотр всех заказов или только своих (в зависимости от роли)
Route::post('/order', [OrderController::class, 'store'])->middleware('auth:api');   // Создание заказа
Route::get('/order/{id}', [OrderController::class, 'show'])->middleware('auth:api');   // Просмотр конкретного заказа
Route::patch('/order/{id}', [OrderController::class, 'update'])->middleware('auth:api');   // Обновление статуса заказа
Route::patch('/order/cancelled/{id}', [OrderController::class, 'destroy'])->middleware('auth:api');   // Отмена заказа
Route::get('/checkout/success', [OrderController::class, 'handleSuccess'])->name('checkout.success');   // Оплачено
Route::get('/checkout/cancel', [OrderController::class, 'handleCancel'])->name('checkout.cancel');   // Отменён

Route::post('/manager', [UserController::class, 'createManager'])->middleware('auth:api');   // Создание менеджера
Route::delete('/manager/{id}', [UserController::class, 'deleteManager'])->middleware('auth:api');   // Удаление менеджера
Route::get('/manager', [UserController::class, 'listManagers'])->middleware('auth:api');   // Список менеджеров

// Пользователи
Route::get('/user', [UserController::class, 'index'])->middleware('auth:api');
Route::get('/user/{id}', [UserController::class, 'show'])->middleware('auth:api');
Route::patch('/user/{id}', [UserController::class, 'update'])->middleware('auth:api');

// Цвета и размеры
Route::get('/colors', [ColorController::class, 'index']);
Route::get('/sizes', [SizeController::class, 'index']);
