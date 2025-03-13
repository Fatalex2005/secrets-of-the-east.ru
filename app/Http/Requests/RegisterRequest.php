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
            'surname' => 'required|string|max:64|min:3',
            'patronymic' => 'nullable|string|max:64|min:3',
            'login' => 'required|string|max:64|unique:users,login,',
            'password' => 'required|string|max:64|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
        ];
    }
    public function messages(): array{
        return [
            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'name.max' => 'Поле "Имя" не должно превышать 64 символа.',
            'name.min' => 'Поле "Имя" не должно быть меньше 3 символов.',

            'surname.required' => 'Поле "Фамилия" обязательно для заполнения.',
            'surname.max' => 'Поле "Фамилия" не должно превышать 64 символа.',
            'surname.min' => 'Поле "Фамилия" не должно быть меньше 3 символов.',

            'patronymic.max' => 'Поле "Отчество" не должно превышать 64 символа.',
            'patronymic.min' => 'Поле "Отчество" не должно быть меньше 3 символов.',

            'login.required' => 'Поле "Логин" обязательно для заполнения.',
            'login.max' => 'Поле "Логин" не должно превышать 64 символа.',
            'login.unique' => 'Такой "Логин" уже существует.',

            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
            'password.max' => 'Поле "Пароль" не должно превышать 64 символа.',
            'password.regex' => 'Пароль должен содержать как минимум одну заглавную букву, одну строчную букву, одну цифру и один специальный символ (@$!%*?&).',
        ];
    }
}
