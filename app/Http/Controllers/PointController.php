<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\PointsRequests\CreatePointRequest;
use App\Http\Requests\PointsRequests\UpdatePointRequest;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointController
{
    // Получение списка всех адресов
    public function index()
    {
        $points = Point::all();
        if ($points->isEmpty()) {
            throw new ApiException( 'Не найдено', 404);
        }
        return response()->json($points)->setStatusCode(200);
    }

    // Создание нового адреса
    public function store(CreatePointRequest $request)
    {
        if(Auth::user()->role->code != 'admin'){
            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }
        $validated = $request->validated();
        // Создание нового адреса
        $point = Point::create([
            ...$validated,
            'city' => $request->city,
            'street' => $request->street,
            'house' => $request->house,
        ]);

        // Возвращаем ответ с созданным объектом и статусом 201 (создано)
        return response()->json($point, 201);
    }

    // Обновление данных адреса
    public function update(UpdatePointRequest $request, $id)
    {
        if (Auth::user()->role->code != 'admin') {
            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }

        $point = Point::find($id);
        if (!$point) {
            return response()->json(['error' => 'Пункт выдачи не найден'], 404);
        }
        $validated = $request->validated();
        // Обновляем данные адреса
        $point->update($validated);

        // Возвращаем обновленный адрес
        return response()->json($point);
    }


    // Удаление адреса по id
    public function destroy($id)
    {
        if (Auth::user()->role->code != 'admin') {
            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }

        $point = Point::find($id);

        if (!$point) {
            return response()->json(['message' => 'Пункт выдачи не найден'], 404);
        }

        // Проверка на наличие связанных записей
        if (
            $point->orders()->exists()
            // добавь сюда другие связи, если есть
        ) {
            return response()->json([
                'message' => 'Нельзя удалить пункт выдачи, так как есть заказы, связанные с ним.'
            ], 409);
        }

        $point->delete();

        return response()->json(['message' => 'Пункт выдачи удалён.'], 200);
    }

}
