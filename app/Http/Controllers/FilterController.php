<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Http\Request;

class FilterController
{
    // Товары по стране
    public function countryIndex($countryId)
    {
        $country = Country::find($countryId);
        if (!$country) {
            throw new ApiException('Страна не найдена', 404);
        }

        $products = Product::with(['category', 'country'])
            ->where('country_id', $countryId)
            ->get();

        if ($products->isEmpty()) {
            throw new ApiException('Товары для указанной страны не найдены', 404);
        }

        return response()->json($products);
    }

    // Товары по категории
    public function categoryIndex($categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            throw new ApiException('Категория не найдена', 404);
        }

        $products = Product::with(['category', 'country'])
            ->where('category_id', $categoryId)
            ->get();

        if ($products->isEmpty()) {
            throw new ApiException('Товары в указанной категории не найдены', 404);
        }

        return response()->json($products);
    }

    // Товары по категории и стране
    public function categoryAndCountryIndex($categoryId, $countryId)
    {
        $category = Category::find($categoryId);
        $country = Country::find($countryId);

        if (!$category) {
            throw new ApiException('Категория не найдена', 404);
        }
        if (!$country) {
            throw new ApiException('Страна не найдена', 404);
        }

        $products = Product::with(['category', 'country'])
            ->where('category_id', $categoryId)
            ->where('country_id', $countryId)
            ->get();

        if ($products->isEmpty()) {
            throw new ApiException('Товары по указанным фильтрам не найдены', 404);
        }

        return response()->json($products);
    }
}
