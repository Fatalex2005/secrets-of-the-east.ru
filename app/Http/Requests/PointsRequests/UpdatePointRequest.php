<?php

namespace App\Http\Requests\PointsRequests;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePointRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city' => 'string|max:100|min:2',
            'street' => 'string|max:100|min:1',
            'house' => 'string|max:10|min:1',
        ];
    }
    public function messages(): array{
        return [
            'city.max' => 'Поле "Город" не должно превышать 100 символов.',
            'city.min' => 'Поле "Город" не должно быть меньше 2 символов.',

            'street.max' => 'Поле "Улица" не должно превышать 100 символов.',
            'street.min' => 'Поле "Улица" не должно быть меньше 1 символа.',

            'house.max' => 'Поле "Дом" не должно превышать 25 символов.',
            'house.min' => 'Поле "Дом" не должно быть меньше 1 символа.',
        ];
    }
}
