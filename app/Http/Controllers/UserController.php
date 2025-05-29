<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController
{
    public function createManager(RegisterRequest $request)
    {
        // Извлекаем role_id для роли 'Менеджер' (предполагаем, что у менеджера code = 'manager')
        $role_id = Role::where('code', 'manager')->first()->id;

        // Если роль не найдена, используем явное значение 2
        if (!$role_id) {
            $role_id = 2;
        }

        // Извлекаем валидированные данные
        $validated = $request->validated();

        // Создаем нового пользователя-менеджера
        $user = User::create([
            ...$validated,
            'role_id' => $role_id
        ]);

        // Создание токена для пользователя
        $user->api_token = Hash::make(Str::random(60));
        $user->save();

        // Возвращаем ответ с токеном
        return response()->json([
            'user' => $user,
            'token' => $user->api_token,
            'message' => 'Менеджер успешно создан'
        ])->setStatusCode(201);
    }
}
