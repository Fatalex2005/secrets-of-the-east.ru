<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;

class ApiException extends HttpResponseException
{
    public function __construct(string $message, int $code = 500, $errors = [])
    {
        $response = [
            'code' => $code,
            'message' => $message,
        ];
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        parent::__construct( response()->json($response)->setStatusCode($code));
    }
}
