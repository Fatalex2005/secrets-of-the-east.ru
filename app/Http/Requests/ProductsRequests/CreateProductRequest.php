<?php

namespace App\Http\Requests\ProductsRequests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo' => 'nullable|text',
            'name' => 'required|string|max:255',
            'description' => 'required|text',
            'sex' => 'required|boolean',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'country_id' => 'required|integer|exists:countries,id',
        ];
    }
    public function messages(): array{
        return [
            'name.required' => 'Поле "Название" обязательно для заполнения.',
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',

            'description.required' => 'Поле "Название" обязательно для заполнения.',

            'sex.required' => 'Поле "Название" обязательно для заполнения.',

            'quantity.required' => 'Поле "Название" обязательно для заполнения.',
            'quantity.min' => 'Поле "Количество" не должно быть ниже 0.',

            'price.required' => 'Поле "Цена" обязательно для заполнения.',
            'price.min' => 'Поле "Цена" не должно быть ниже 0.',

            'category_id' => 'Категория должна быть выбрана',
            'country_id' => 'Страна производителя должна быть выбрана',
        ];
    }
}
