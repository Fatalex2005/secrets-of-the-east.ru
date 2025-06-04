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

        $products = $cartProducts->map(function ($cartProduct) {
            $pcs = $cartProduct->productColorSize;

            return [
                'id' => $pcs->product->id,
                'name' => $pcs->product->name,
                'price' => $pcs->product->price,
                'photo' => $pcs->product->photo,
                'description' => $pcs->product->description,
                'color' => $pcs->color->name ?? null,
                'size' => $pcs->size->name ?? null,
                'quantity' => $cartProduct->quantity,
            ];
        });

        $totalCost = $products->sum(function ($product) {
            return $product['price'] * $product['quantity'];
        });

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

        $pcs = ProductColorSize::find($productColorSizeId);

        if (!$pcs) {
            return response()->json(['message' => 'Товар не найден'], 404);
        }

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
    /**
     * Удаление товара из корзины
     *
     * @param Request $request
     * @param int $productColorSizeId
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @response 404 {
     *   "status": "error",
     *   "message": "Указанный товар не найден в вашей корзине"
     * }
     */
    public function removeProduct(Request $request, $productColorSizeId)
    {
        $clientId = $request->user()->id;

        try {
            // Проверяем существование товара в корзине пользователя
            $cartItem = Cart::where('user_id', $clientId)
                ->where('product_color_size_id', $productColorSizeId)
                ->firstOrFail();

            // Удаляем товар из корзины
            $cartItem->delete();

            // Получаем обновленную корзину
            $cartProducts = $this->getUserCartProducts($clientId);
            $totalCost = $cartProducts->sum('total');

            return response()->json([
                'status' => 'success',
                'message' => 'Товар успешно удалён из корзины',
                'data' => [
                    'total_cost' => $totalCost,
                    'remaining_items' => $cartProducts->count()
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Указанный товар не найден в вашей корзине'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Произошла ошибка при удалении товара из корзины: ' . $e->getMessage()
            ], 500);
        }
    }

    // Вспомогательный метод для получения корзины пользователя
    private function getUserCartProducts($clientId)
    {
        return Cart::where('user_id', $clientId)->get();
    }
}
