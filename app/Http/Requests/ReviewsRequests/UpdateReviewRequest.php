<?php

namespace App\Http\Requests\ReviewsRequests;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => 'integer|max:5|min:1',
            'description' => 'string|max:255',
        ];
    }
    public function messages(): array{
        return [
            'rating.max' => 'Поле "Рейтинг" не должно превышать 5.',
            'rating.min' => 'Поле "Рейтинг" не должно быть меньше 1.',

            'description.max' => 'Поле "Описание" не должно превышать 255 символов.',
        ];
    }
}
