<?php

namespace App\Http\Requests\ProductsRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo' => 'nullable|text',
            'name' => 'string|max:255',
            'description' => 'text',
            'sex' => 'boolean',
            'quantity' => 'integer|min:0',
            'price' => 'numeric|min:0',
            'category_id' => 'integer|exists:categories,id',
            'country_id' => 'integer|exists:countries,id',
        ];
    }
    public function messages(): array{
        return [
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',

            'quantity.min' => 'Поле "Количество" не должно быть ниже 0.',

            'price.min' => 'Поле "Цена" не должно быть ниже 0.',

            'category_id' => 'Категория должна быть выбрана',
            'country_id' => 'Страна производителя должна быть выбрана',
        ];
    }
}
