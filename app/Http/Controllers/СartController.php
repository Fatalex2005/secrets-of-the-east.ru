<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductColorSize;
use Illuminate\Http\Request;

class СartController
{
    // Получение корзины с товарами для пользователя
    public function show(Request $request)
    {
        $clientId = $request->user()->id;

        $cartProducts = Cart::with(['productColorSize.product', 'productColorSize.color', 'productColorSize.size'])
            ->where('user_id', $clientId)
            ->get();

        if ($cartProducts->isEmpty()) {
            return response()->json(['message' => 'Корзина пуста'], 404);
        }

        // Группируем по product_id
        $grouped = $cartProducts->groupBy(function ($item) {
            return $item->productColorSize->product->id;
        });

        $products = $grouped->map(function ($group) {
            $firstItem = $group->first();
            $product = $firstItem->productColorSize->product;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'photo' => $product->photo,
                'description' => $product->description,
                'variants' => $group->map(function ($item) {
                    return [
                        'color' => $item->productColorSize->color->name ?? null,
                        'size' => $item->productColorSize->size->name ?? null,
                        'quantity' => $item->quantity,
                        'total' => $item->total,
                    ];
                })->values(),
            ];
        })->values();

        $totalCost = $cartProducts->sum('total');

        return response()->json([
            'total_cost' => $totalCost,
            'created_at' => $cartProducts->first()->created_at,
            'updated_at' => $cartProducts->first()->updated_at,
            'products' => $products,
        ]);
    }

    // Добавление товара в корзину
    public function addProduct(Request $request, $productColorSizeId)
    {
        $clientId = $request->user()->id;
        $quantity = $request->input('quantity', 1);

        $pcs = \App\Models\ProductColorSize::findOrFail($productColorSizeId);

        $existingCartProduct = Cart::where('user_id', $clientId)
            ->where('product_color_size_id', $productColorSizeId)
            ->first();

        if ($existingCartProduct) {
            $existingCartProduct->quantity += $quantity;

            // Пересчёт total
            $existingCartProduct->total = $pcs->product->price * $existingCartProduct->quantity;

            $existingCartProduct->save();
        } else {
            Cart::create([
                'user_id' => $clientId,
                'product_color_size_id' => $productColorSizeId,
                'quantity' => $quantity,
                'total' => $pcs->product->price * $quantity,
            ]);
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Товар добавлен в корзину',
        ]);
    }

    // Обновление количества товара в корзине
    public function updateProduct(Request $request, $productColorSizeId)
    {
        $clientId = $request->user()->id;
        $quantity = $request->input('quantity');

        if ($quantity < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Количество товара должно быть больше 0'
            ], 400);
        }

        $cartProduct = Cart::where('user_id', $clientId)
            ->where('product_color_size_id', $productColorSizeId)
            ->with('productColorSize.product')
            ->first();

        if (!$cartProduct) {
            return response()->json([
                'status' => 'error',
                'message' => 'Товар не найден в корзине'
            ], 404);
        }

        $cartProduct->quantity = $quantity;
        $cartProduct->total = $cartProduct->productColorSize->product->price * $quantity;
        $cartProduct->save();

        // Пересчёт всей корзины
        $cartProducts = $this->getUserCartProducts($clientId);
        $totalCost = $cartProducts->sum('total');

        return response()->json([
            'status' => 'success',
            'message' => 'Количество товара в корзине успешно обновлено',
            'data' => [
                'total_cost' => $totalCost
            ]
        ]);
    }

    // Удаление товара из корзины
    public function removeProduct(Request $request, $productColorSizeId)
    {
        $clientId = $request->user()->id;

        Cart::where('user_id', $clientId)
            ->where('product_color_size_id', $productColorSizeId)
            ->delete();

        $cartProducts = $this->getUserCartProducts($clientId);
        $totalCost = $cartProducts->sum('total');

        return response()->json([
            'status' => 'success',
            'message' => 'Товар успешно удалён из корзины',
            'data' => [
                'total_cost' => $totalCost
            ]
        ]);
    }

    // Вспомогательный метод для получения корзины пользователя
    private function getUserCartProducts($clientId)
    {
        return Cart::where('user_id', $clientId)->get();
    }
}
