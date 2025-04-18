<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\ProductsRequests\CreateProductRequest;
use App\Http\Requests\ProductsRequests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductColorSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController
{
    // Метод для просмотра всех товаров
    public function index()
    {
        $products = Product::all();
        if ($products->isEmpty()) {
            throw new ApiException( 'Не найдено', 404);
        }
        return response()->json($products)->setStatusCode(200);
    }
    // Метод для создания товара
    public function store(CreateProductRequest $request)
    {
        if(Auth::user()->role->code != 'admin'){

            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }

        // Сохраняем фото товара в public папке
        $photoPath = $request->file('photo')->store('products', 'public');

        // Генерируем полный URL для фото
        $photoUrl = url('storage/' . $photoPath);

        // Считаем общее количество из цветов и размеров
        $totalQuantity = 0;
        foreach ($request->colors as $color) {
            foreach ($color['sizes'] as $size) {
                $totalQuantity += $size['quantity'];
            }
        }
        // Создаём товар
        $product = Product::create([
            'photo' => $photoUrl,
            'name' => $request->name,
            'description' => $request->description,
            'sex' => $request->sex,
            'quantity' => $totalQuantity,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'country_id' => $request->country_id,
        ]);
        // Заполняем таблицу product_color_size
        foreach ($request->colors as $color) {
            foreach ($color['sizes'] as $size) {
                ProductColorSize::create([
                    'product_id' => $product->id,
                    'color_id' => $color['color_id'],
                    'size_id' => $size['size_id'],
                    'quantity' => $size['quantity'],
                ]);
            }
        }
        return response()->json($product, 201);
    }
    // Метод для просмотра товара
    public function show($id)
    {
        $product = Product::with(['productColorSizes.color', 'productColorSizes.size'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Товар не найден'], 404);
        }

        return response()->json($product, 200);
    }
    public function update(UpdateProductRequest $request, $id)
    {
        if (Auth::user()->role->code != 'admin') {
            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Товар не найден'], 404);
        }

        // Если есть новое фото — сохраняем
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('products', 'public');
            $photoUrl = url('storage/' . $photoPath);
            $product->photo = $photoUrl;
        }

        // Считаем новое количество
        $totalQuantity = 0;
        foreach ($request->colors as $color) {
            foreach ($color['sizes'] as $size) {
                $totalQuantity += $size['quantity'];
            }
        }

        // Обновляем данные товара
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'sex' => $request->sex,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'country_id' => $request->country_id,
            'quantity' => $totalQuantity,
        ]);

        // Удаляем старые размеры и цвета
        $product->productColorSizes()->delete();

        // Добавляем новые
        foreach ($request->colors as $color) {
            foreach ($color['sizes'] as $size) {
                $product->productColorSizes()->create([
                    'color_id' => $color['color_id'],
                    'size_id' => $size['size_id'],
                    'quantity' => $size['quantity'],
                ]);
            }
        }

        return response()->json($product->load(['productColorSizes.color', 'productColorSizes.size']), 200);
    }
    public function destroy($id)
    {
        if (Auth::user()->role->code != 'admin') {
            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Товар не найден'], 404);
        }

        try {
            // Поиск товара с учетом связанных данных
            $product = Product::with('productColorSizes')->find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Товар не найден',
                    'success' => false
                ], 404);
            }

            // Удаление связанных записей (цвета/размеры)
            $product->productColorSizes()->delete();

            // Удаление самого товара
            $product->delete();

            return response()->json([
                'message' => 'Товар успешно удален',
                'success' => true
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            // Код ошибки для нарушения ограничения внешнего ключа
            if ($e->getCode() == '23000') {
                return response()->json([
                    'message' => 'Невозможно удалить товар: существуют связанные записи в корзине или других таблицах',
                    'success' => false
                ], 409); // 409 Conflict - подходящий код статуса
            }

            // Другие ошибки БД
            return response()->json([
                'message' => 'Произошла ошибка при удалении товара',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
