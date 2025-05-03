<?php

namespace App\Http\Requests;

use App\Exceptions\ApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
    // Вызов исключения при провале валидации данных
    public function failedValidation(Validator $validator)
    {
        throw new ApiException('Ошибка валидации данных', 422, $validator->errors());
    }
    // Вызов исключения при провале авторизации
    public function failedAuthorization()
    {
        throw new ApiException('Ошибка доступа пользователя', 403);
    }
}
