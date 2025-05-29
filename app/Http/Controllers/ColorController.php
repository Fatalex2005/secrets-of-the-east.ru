<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;
use App\Models\Color;

class ColorController
{
    public function index()
    {
        $colors = Color::all();
        if ($colors->isEmpty()) {
            throw new ApiException( 'Не найдено', 404);
        }
        return response()->json($colors)->setStatusCode(200);
    }
}
