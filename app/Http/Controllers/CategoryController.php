<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriesRequests\CreateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController
{
    public function store(CreateCategoryRequest $request) {

        if (Auth::user()->role->code != 'admin') {
            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }

        $validated = $request->validated();
        // Создание нового адреса
        $category = Category::create([
            ...$validated,
            'name' => $request->name,
        ]);

        // Возвращаем ответ с созданным объектом и статусом 201 (создано)
        return response()->json($category, 201);
    }
}
