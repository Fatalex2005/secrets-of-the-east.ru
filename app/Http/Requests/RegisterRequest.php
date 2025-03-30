<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:64|min:3',
            'email' => 'required|string|max:64|min:3|unique:users,email,',
            'telephone' => 'nullable|string|max:25|min:10',
            'sex' => 'required|boolean',
            'password' => 'required|string|max:64|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
        ];
    }
    public function messages(): array{
        return [
            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'name.max' => 'Поле "Имя" не должно превышать 64 символа.',
            'name.min' => 'Поле "Имя" не должно быть меньше 3 символов.',

            'email.required' => 'Поле "Email" обязательно для заполнения.',
            'email.max' => 'Поле "Email" не должно превышать 64 символа.',
            'email.min' => 'Поле "Email" не должно быть меньше 3 символов.',
            'email.unique' => 'Пользователь с вашей почтой уже зарегистрирован',

            'telephone.max' => 'Поле "Телефон" не должно превышать 64 символа.',
            'telephone.min' => 'Поле "Телефон" не должно быть меньше 3 символов.',

            'sex.required' => 'Поле "Пол" обязательно для заполнения.',

            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
            'password.max' => 'Поле "Пароль" не должно превышать 64 символа.',
            'password.regex' => 'Пароль должен содержать как минимум одну заглавную букву, одну строчную букву, одну цифру и один специальный символ (@$!%*?&).',
        ];
    }
}
