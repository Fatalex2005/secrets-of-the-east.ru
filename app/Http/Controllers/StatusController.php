<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController
{
    public function index()
    {
        $statuses = Status::all();
        if ($statuses->isEmpty()) {
            throw new ApiException( 'Не найдено', 404);
        }
        return response()->json($statuses)->setStatusCode(200);
    }
}
