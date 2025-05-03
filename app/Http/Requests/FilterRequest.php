<?php

namespace App\Http\Requests;

class FilterRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|integer|exists:categories,id',
            'country_id' => 'sometimes|integer|exists:countries,id',
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:1|gt:min_price',
            'sex' => 'sometimes|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'exists' => 'Указанный :attribute не существует',
            'gt' => 'Максимальная цена должна быть больше минимальной',

            'category_id.integer' => 'ID категории должно быть целым числом',
            'country_id.integer' => 'ID страны должно быть целым числом',

            'min_price.numeric' => 'Минимальная цена должна быть числом',
            'max_price.numeric' => 'Максимальная цена должна быть числом',
            'min_price.min' => 'Минимальная цена не может быть меньше 0',
            'max_price.min' => 'Максимальная цена не может быть меньше 1',

            'sex.boolean' => 'Пол должен быть true или false, 1 или 0'
        ];
    }
}
