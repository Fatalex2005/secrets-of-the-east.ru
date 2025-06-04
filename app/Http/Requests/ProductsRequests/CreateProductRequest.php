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
            'colors.*.color_id' => 'nullable|exists:colors,id',
            'colors.*.new_color_name' => 'nullable|required_with:colors.*.new_color_hex|string|max:255',
            'colors.*.new_color_hex' => 'nullable|required_with:colors.*.new_color_name|string|regex:/^#[a-fA-F0-9]{6}$/',
            'colors.*.sizes' => 'required|array',
            'colors.*.sizes.*.size_id' => 'nullable|exists:sizes,id',
            'colors.*.sizes.*.new_size_name' => 'nullable|string|max:255',
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
            'price.min' => 'Поле "Цена" не может быть ниже 0.',

            'category_id' => 'Категория должна быть выбрана',
            'category_id.exists' => 'Выбранной категории не существует',
            'country_id' => 'Страна производителя должна быть выбрана',
            'country_id.exists' => 'Выбранной страны не существует',

            'colors.required' => 'Необходимо указать хотя бы один цвет.',
            'colors.min' => 'Необходимо указать хотя бы один цвет.',
            'colors.max' => 'Нельзя указать более 20 цветов.',

            'colors.*.color_id.exists' => 'Выбранный цвет не существует или был удален.',
            'colors.*.color_id.required_without_all' => 'Укажите существующий цвет или создайте новый.',

            'colors.*.new_color_name.string' => 'Название нового цвета должно быть строкой.',
            'colors.*.new_color_name.max' => 'Название нового цвета не должно превышать 100 символов.',
            'colors.*.new_color_name.required_with' => 'Название цвета обязательно при указании HEX-кода.',
            'colors.*.new_color_name.required_without' => 'Укажите название нового цвета или выберите существующий.',

            'colors.*.new_color_hex.regex' => 'HEX-код цвета должен быть в формате #FFFFFF.',
            'colors.*.new_color_hex.required_with' => 'HEX-код цвета обязателен при указании названия.',
            'colors.*.new_color_hex.required_without' => 'Укажите HEX-код нового цвета или выберите существующий.',

            'colors.*.sizes.required' => 'Для каждого цвета укажите хотя бы один размер.',
            'colors.*.sizes.min' => 'Для каждого цвета укажите хотя бы один размер.',
            'colors.*.sizes.max' => 'Для каждого цвета нельзя указать более 20 размеров.',

            'colors.*.sizes.*.size_id.exists' => 'Выбранный размер не существует или был удален.',
            'colors.*.sizes.*.size_id.required_without' => 'Укажите существующий размер или создайте новый.',

            'colors.*.sizes.*.new_size_name.string' => 'Название нового размера должно быть строкой.',
            'colors.*.sizes.*.new_size_name.max' => 'Название нового размера не должно превышать 50 символов.',
            'colors.*.sizes.*.new_size_name.required_without' => 'Укажите название нового размера или выберите существующий.',

            'colors.*.sizes.*.quantity.required' => 'Укажите количество товара для размера.',
            'colors.*.sizes.*.quantity.integer' => 'Количество должно быть целым числом.',
            'colors.*.sizes.*.quantity.min' => 'Количество не может быть отрицательным.',
            'colors.*.sizes.*.quantity.max' => 'Количество не может превышать 10000.',
        ];
    }
}
