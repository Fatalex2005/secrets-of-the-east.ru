<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\RegisterRequest;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Регистрация пользователя
    public function register(RegisterRequest $request)
    {
        // Извлекаем role_id для роли 'Пользователь'
        $role_id = Role::where('code', 'user')->first()->id;

        // Извлекаем валидированные данные
        $validated = $request->validated();

        // Создаем нового пользователя
        $user = User::create([
            ...$validated,
            'role_id' => $role_id]);

        // Создание токена для пользователя
        $user->api_token = Hash::make(Str::random(60));
        $user->save();

        // Возвращаем ответ с токеном
        return response()->json([
            'user' => $user,
            'token' => $user->api_token
        ])->setStatusCode(201);
    }
    // Авторизация
    public function login(Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw new ApiException("Неверная почта или пароль", 401);
        }
        // Получение текущего пользователя
        $user = Auth::user();
        // Создание нового токена для пользователя
        $user->api_token = Hash::make(Str::random(60));
        $user->save();
        return response()->json(['token' => $user->api_token])->setStatusCode(200);
    }
    // Выход
    public function logout(Request $request) {
        // Получение текущего пользователя
        $user = Auth::user();
        // Создание нового токена для пользователя
        $user->api_token = null;
        $user->save();
        return response()->json(['message' => 'Вы вышли из системы'])->setStatusCode(200);
    }
}
