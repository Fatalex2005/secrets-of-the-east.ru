<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController
{
    // Просмотр профиля
    public function show()
    {
        $user = Auth::user()->load('role');

        return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telephone' => $user->telephone,
                'sex' => $user->sex,
                'role' => $user->role->name ?? null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
        ]);
    }
    // Обновление профиля
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        // Извлекаем валидированные данные
        $validated = $request->validated();

        $user->update($validated);

        return response()->json([
            'message' => 'Профиль успешно обновлен',
            'data' => $user->fresh()->load('role')
        ]);
    }
}
