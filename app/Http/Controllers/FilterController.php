<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\FilterRequest;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Http\Request;

class FilterController
{
    public function filterProducts(FilterRequest $request)
    {
        // Валидация входных параметров
        $validated = $request->validated();

        // Начинаем построение запроса
        $query = Product::query()->with(['category', 'country']);

        // Применяем фильтры
        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);
            if (!$category) {
                throw new ApiException('Категория не найдена', 404);
            }
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('country_id')) {
            $country = Country::find($request->country_id);
            if (!$country) {
                throw new ApiException('Страна не найдена', 404);
            }
            $query->where('country_id', $request->country_id);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('sex')) {
            $query->where('sex', $request->sex);
        }

        // Получаем и возвращаем результаты
        $products = $query->get();

        if ($products->isEmpty()) {
            throw new ApiException('Товары по заданным фильтрам не найдены', 404);
        }

        return response()->json([
            'success' => true,
            'filters' => $validated,
            'count' => $products->count(),
            'data' => $products
        ]);
    }
}
