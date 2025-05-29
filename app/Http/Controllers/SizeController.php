<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController
{
    public function index()
    {
        $sizes = Size::all();
        if ($sizes->isEmpty()) {
            throw new ApiException( 'Не найдено', 404);
        }
        return response()->json($sizes)->setStatusCode(200);
    }
}
