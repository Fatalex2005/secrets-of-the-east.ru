<?php

namespace App\Http\Requests\ProductsRequests;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo' => 'nullable|file|image|max:2048',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sex' => 'required|boolean',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'country_id' => 'required|integer|exists:countries,id',
            'colors' => 'required|array',
            'colors.*.color_id' => 'required|exists:colors,id',
            'colors.*.sizes' => 'required|array',
            'colors.*.sizes.*.size_id' => 'required|exists:sizes,id',
            'colors.*.sizes.*.quantity' => 'required|integer|min:0',
        ];
    }
    public function messages(): array{
        return [
            'name.required' => 'Поле "Название" обязательно для заполнения.',
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',

            'description.required' => 'Поле "Описание" обязательно для заполнения.',

            'sex.required' => 'Поле "Пол" обязательно для заполнения.',

            'price.required' => 'Поле "Цена" обязательно для заполнения.',
            'price.min' => 'Поле "Цена" не должно быть ниже 0.',

            'category_id' => 'Категория должна быть выбрана',
            'country_id' => 'Страна производителя должна быть выбрана',
        ];
    }
}
