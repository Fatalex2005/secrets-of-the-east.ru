<?php

namespace App\Http\Requests\ReviewsRequests;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateReviewRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => 'required|integer|max:5|min:1',
            'description' => 'required|string|max:255',
        ];
    }
    public function messages(): array{
        return [
            'rating.required' => 'Поле "Рейтинг" обязательно для заполнения.',
            'rating.max' => 'Поле "Рейтинг" не должно превышать 5.',
            'rating.min' => 'Поле "Рейтинг" не должно быть меньше 1.',

            'description.required' => 'Поле "Описание" обязательно для заполнения.',
            'description.max' => 'Поле "Описание" не должно превышать 255 символов.',
        ];
    }
}
