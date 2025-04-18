<?php

namespace App\Http\Requests\ProfileRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:64|min:3',
            'email' => 'string|max:64|min:3|unique:users,email,',
            'telephone' => 'string|max:25|min:10',
            'sex' => 'boolean',
            'password' => 'string|max:64|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
        ];
    }
    public function messages(): array{
        return [
            'name.max' => 'Поле "Имя" не должно превышать 64 символа.',
            'name.min' => 'Поле "Имя" не должно быть меньше 3 символов.',

            'email.max' => 'Поле "Email" не должно превышать 64 символа.',
            'email.min' => 'Поле "Email" не должно быть меньше 3 символов.',
            'email.unique' => 'Пользователь с вашей почтой уже существует',

            'telephone.max' => 'Поле "Телефон" не должно превышать 25 символов.',
            'telephone.min' => 'Поле "Телефон" не должно быть меньше 3 символов.',

            'sex.required' => 'Поле "Пол" обязательно для заполнения.',

            'password.max' => 'Поле "Пароль" не должно превышать 64 символа.',
            'password.regex' => 'Пароль должен содержать как минимум одну заглавную букву, одну строчную букву, одну цифру и один специальный символ (@$!%*?&).',
        ];
    }
}
