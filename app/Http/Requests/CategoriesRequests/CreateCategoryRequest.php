<?php

namespace App\Http\Requests\CategoriesRequests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:64|min:1',
        ];
    }
    public function messages(): array{
        return [
            'city.required' => 'Поле "Название" обязательно для заполнения.',
            'city.max' => 'Поле "Название" не должно превышать 64 символа.',
            'city.min' => 'Поле "Название" не должно быть меньше 1 символа.',
        ];
    }
}
