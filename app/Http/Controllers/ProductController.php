<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\ProductsRequests\CreateProductRequest;
use App\Http\Requests\ProductsRequests\UpdateProductRequest;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColorSize;
use App\Models\Size;
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
    public function store(CreateProductRequest $request)
    {
        if (Auth::user()->role->code != 'admin' && Auth::user()->role->code != 'manager'){
            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }

        // Обработка фото товара
        $photoUrl = null;
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photoPath = $request->file('photo')->store('products', 'public');
            $photoUrl = url('storage/' . $photoPath);
        }

        // Создаём товар с начальным quantity = 0
        $product = Product::create([
            'photo' => $photoUrl,
            'name' => $request->name,
            'description' => $request->description,
            'sex' => $request->sex,
            'quantity' => 0, // Устанавливаем начальное значение
            'price' => $request->price,
            'category_id' => $request->category_id,
            'country_id' => $request->country_id,
        ]);

        $totalQuantity = 0;

        // Обработка цветов и размеров
        foreach ($request->colors as $colorData) {
            // Обработка цвета (существующий или новый)
            if (isset($colorData['color_id'])) {
                // Используем существующий цвет
                $colorId = $colorData['color_id'];
            } else {
                // Создаем новый цвет
                $color = Color::create([
                    'name' => $colorData['new_color_name'],
                    'hex' => $colorData['new_color_hex'],
                ]);
                $colorId = $color->id;
            }

            // Обработка размеров для текущего цвета
            foreach ($colorData['sizes'] as $sizeData) {
                // Обработка размера (существующий или новый)
                if (isset($sizeData['size_id'])) {
                    // Используем существующий размер
                    $sizeId = $sizeData['size_id'];
                } else {
                    // Создаем новый размер
                    $size = Size::create([
                        'name' => $sizeData['new_size_name'],
                    ]);
                    $sizeId = $size->id;
                }

                // Создаем связь продукта с цветом и размером
                ProductColorSize::create([
                    'product_id' => $product->id,
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                    'quantity' => $sizeData['quantity'],
                ]);

                $totalQuantity += $sizeData['quantity'];
            }
        }

        // Обновляем общее количество товара
        $product->update(['quantity' => $totalQuantity]);

        return response()->json([
            'product' => $product,
            'message' => 'Товар успешно создан с цветами и размерами'
        ], 201);
    }
    // Метод для просмотра товара
    public function show($id)
    {
        $product = Product::with(['category','country','productColorSizes.color', 'productColorSizes.size'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Товар не найден'], 404);
        }

        return response()->json($product, 200);
    }
    public function update(UpdateProductRequest $request, $id)
    {
        // Проверка прав доступа
        if (Auth::user()->role->code != 'admin' && Auth::user()->role->code != 'manager') {
            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }

        // Поиск товара с его вариантами
        $product = Product::with(['productColorSizes.carts', 'productColorSizes.orderItems'])->find($id);
        if (!$product) {
            return response()->json(['message' => 'Товар не найден'], 404);
        }

        // Обработка основного фото
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photoPath = $request->file('photo')->store('products', 'public');
            $photoUrl = url('storage/' . $photoPath);
            $product->photo = $photoUrl;
        }

        $totalQuantity = 0;
        $usedPcsIds = [];

        // Обработка цветов и размеров
        foreach ($request->colors as $colorData) {
            $colorId = $this->getOrCreateColor($colorData);

            foreach ($colorData['sizes'] as $sizeData) {
                $sizeId = $this->getOrCreateSize($sizeData);

                // Ищем или создаем связь товара с цветом и размером
                $pcs = ProductColorSize::firstOrNew([
                    'product_id' => $product->id,
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                ]);

                $pcs->quantity = $sizeData['quantity'];
                $pcs->save();
                $usedPcsIds[] = $pcs->id;
                $totalQuantity += $sizeData['quantity'];
            }
        }

        // Удаляем только те варианты, которых:
        // 1. Нет в новом запросе
        // 2. Нет в корзинах
        // 3. Нет в заказах
        $toDelete = $product->productColorSizes()
            ->whereNotIn('id', $usedPcsIds)
            ->whereDoesntHave('carts')
            ->whereDoesntHave('orderItems')
            ->get();

        foreach ($toDelete as $pcs) {
            $pcs->delete();
        }

        // Обновляем основные данные товара
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'sex' => $request->sex,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'country_id' => $request->country_id,
            'quantity' => $totalQuantity,
        ]);

        return response()->json([
            'product' => $product->fresh()->load(['productColorSizes.color', 'productColorSizes.size']),
            'message' => 'Товар успешно обновлен',
            'warning' => $toDelete->count() < count($product->productColorSizes) - count($usedPcsIds)
                ? 'Некоторые варианты не были удалены, так как они используются в заказах или корзинах'
                : null
        ], 200);
    }

    protected function getOrCreateColor($colorData)
    {
        if (isset($colorData['color_id'])) {
            return $colorData['color_id'];
        }

        return Color::create([
            'name' => $colorData['new_color_name'],
            'hex' => $colorData['new_color_hex'],
        ])->id;
    }

    protected function getOrCreateSize($sizeData)
    {
        if (isset($sizeData['size_id'])) {
            return $sizeData['size_id'];
        }

        return Size::create([
            'name' => $sizeData['new_size_name'],
        ])->id;
    }
    public function destroy($id)
    {
        if (Auth::user()->role->code != 'admin' && Auth::user()->role->code != 'manager'){
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
