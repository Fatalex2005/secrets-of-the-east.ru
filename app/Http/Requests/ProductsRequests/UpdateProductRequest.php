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
            'photo' => 'nullable|file|image|max:2048',
            'name' => 'string|max:255',
            'description' => 'string',
            'sex' => 'boolean',
            'price' => 'numeric|min:0',
            'category_id' => 'integer|exists:categories,id',
            'country_id' => 'integer|exists:countries,id',
            'colors' => 'nullable|array',
            'colors.*.color_id' => 'nullable|integer|exists:colors,id',
            'colors.*.sizes' => 'nullable|array',
            'colors.*.sizes.*.size_id' => 'nullable|integer|exists:sizes,id',
            'colors.*.sizes.*.quantity' => 'nullable|integer|min:0',
        ];
    }
    public function messages(): array{
        return [
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',

            'price.min' => 'Поле "Цена" не должно быть ниже 0.',

            'category_id' => 'Категория должна быть выбрана',
            'country_id' => 'Страна производителя должна быть выбрана',
        ];
    }
}
