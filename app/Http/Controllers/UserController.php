<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController
{
    public function createManager(RegisterRequest $request)
    {
        if (Auth::user()->role->code != 'admin') {
            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }
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
    // Удаление менеджера (для админов и менеджеров)
    public function deleteManager($id)
    {
        $currentUser = Auth::user();
        $managerToDelete = User::with('role')->findOrFail($id);

        // Проверяем, что удаляемый пользователь - менеджер
        if ($managerToDelete->role->code !== 'manager') {
            return response()->json(['message' => 'Можно удалять только менеджеров'], 400);
        }

        // Проверяем права текущего пользователя
        if (!in_array($currentUser->role->code, ['admin'])) {
            return response()->json(['message' => 'Недостаточно прав для удаления менеджера'], 403);
        }

        $managerToDelete->delete();

        return response()->json([
            'message' => 'Менеджер успешно удален',
            'deleted_manager' => $this->formatManagerData($managerToDelete)
        ]);
    }

    // Список всех менеджеров (для админов и менеджеров)
    public function listManagers()
    {
        $currentUser = Auth::user();

        if (!in_array($currentUser->role->code, ['admin', 'manager'])) {
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }

        $managers = User::whereHas('role', function($query) {
            $query->where('code', 'manager');
        })->get()->map(function($manager) {
            return $this->formatManagerData($manager);
        });

        return response()->json($managers);
    }

    // Форматирование данных менеджера
    protected function formatManagerData($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'telephone' => $user->telephone,
            'role' => $user->role->name,
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
        ];
    }
    // Просмотр всех пользователей (для админа)
    public function index()
    {
        if (Auth::user()->role->code !== 'admin') {
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }

        $users = User::with('role')->get()->map(function ($user) {
            return $this->formatUserData($user);
        });

        return response()->json($users);
    }

    // Просмотр конкретного пользователя
    public function show($id)
    {
        $currentUser = Auth::user();
        $requestedUser = User::with('role')->findOrFail($id);

        // Проверка прав: админ или просмотр своего профиля
        if ($currentUser->role->code !== 'admin' && $currentUser->id != $id) {
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }

        return response()->json($this->formatUserData($requestedUser));
    }

    // Обновление пользователя
    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        // Только админ может изменять других пользователей
        if ($currentUser->role->code !== 'admin' && $currentUser->id != $id) {
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }

        // Валидация
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'telephone' => 'sometimes|string|max:20',
            'sex' => 'sometimes|boolean',
            'role_id' => 'sometimes|integer|exists:roles,id'
        ]);

        // Только админ может менять роль
        if (isset($validated['role_id'])) {
            if ($currentUser->role->code !== 'admin') {
                return response()->json(['message' => 'Недостаточно прав для изменения роли'], 403);
            }

            // Запрещаем изменять роль последнему админу
            if ($user->role->code === 'admin' &&
                User::where('role_id', $user->role_id)->count() <= 1) {
                return response()->json(['message' => 'Нельзя изменить роль последнему администратору'], 400);
            }
        }

        $user->update($validated);

        return response()->json([
            'user' => $this->formatUserData($user->fresh('role')),
            'message' => 'Данные пользователя обновлены'
        ]);
    }

    // Форматирование данных пользователя
    protected function formatUserData($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'telephone' => $user->telephone,
            'sex' => $user->sex,
            'role' => [
                'id' => $user->role->id,
                'name' => $user->role->name,
                'code' => $user->role->code
            ],
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
