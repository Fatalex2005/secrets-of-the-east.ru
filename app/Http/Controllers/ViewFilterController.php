<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\Category;
use App\Models\Country;
use Illuminate\Http\Request;

class ViewFilterController
{
    public function allCountryIndex()
    {
        $countries = Country::all();
        if ($countries->isEmpty()) {
            throw new ApiException('Не найдено', 404);
        }
        return response()->json($countries)->setStatusCode(200);
    }

    public function allCategoryIndex()
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            throw new ApiException( 'Не найдено', 404);
        }
        return response()->json($categories)->setStatusCode(200);
    }
}
