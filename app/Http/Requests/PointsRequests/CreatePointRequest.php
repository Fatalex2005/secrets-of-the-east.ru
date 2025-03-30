<?php

namespace App\Http\Requests\PointsRequests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePointRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city' => 'required|string|max:100|min:2',
            'street' => 'required|string|max:100|min:1',
            'house' => 'required|string|max:10|min:1',
        ];
    }
    public function messages(): array{
        return [
            'city.required' => 'Поле "Город" обязательно для заполнения.',
            'city.max' => 'Поле "Город" не должно превышать 100 символов.',
            'city.min' => 'Поле "Город" не должно быть меньше 2 символов.',

            'street.required' => 'Поле "Улица" обязательно для заполнения.',
            'street.max' => 'Поле "Улица" не должно превышать 100 символов.',
            'street.min' => 'Поле "Улица" не должно быть меньше 1 символа.',

            'house.required' => 'Поле "Дом" обязательно для заполнения.',
            'house.max' => 'Поле "Дом" не должно превышать 25 символов.',
            'house.min' => 'Поле "Дом" не должно быть меньше 1 символа.',
        ];
    }
}
