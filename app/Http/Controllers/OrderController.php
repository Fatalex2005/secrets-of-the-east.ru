<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class OrderController
{
    // Получение списка всех заказов пользователя
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->role->code === 'admin'; // предполагается, что роль хранится в name

        // Загружаем заказы с нужными связями
        $orders = $isAdmin
            ? Order::with([
                'user', 'point', 'status',
                'orderItems.productColorSize.product',
                'orderItems.productColorSize.color',
                'orderItems.productColorSize.size'
            ])->get()
            : $user->orders()
                ->with([
                    'point', 'status',
                    'orderItems.productColorSize.product',
                    'orderItems.productColorSize.color',
                    'orderItems.productColorSize.size'
                ])->get();

        // Трансформируем заказы
        $ordersTransformed = $orders->map(function ($order) use ($isAdmin) {
            $totalCost = $order->orderItems->sum(function ($item) {
                return $item->productColorSize->product->price * $item->quantity;
            });

            return [
                'id' => $order->id,
                'order_date' => $order->order_date,
                'status' => $order->status->name ?? null,
                'total_cost' => $totalCost,
                'user' => $isAdmin ? $order->user->name : null,
                'address' => [
                    'city' => $order->point->city ?? null,
                    'street' => $order->point->street ?? null,
                    'house' => $order->point->house ?? null,
                ]
            ];
        });

        return response()->json($ordersTransformed);
    }

    // Создание нового заказа
    // Создание нового заказа с тестовой оплатой
    public function store(Request $request)
    {
        $clientId = $request->user()->id;
        $addressId = $request->input('point_id');

        // Проверка наличия адреса
        if (!$addressId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Не указан адрес доставки (point_id).',
            ], 400);
        }

        // Загружаем корзину с нужными связями
        $cartProducts = Cart::where('user_id', $clientId)
            ->with('productColorSize.product')
            ->get();

        if ($cartProducts->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Корзина пуста. Добавьте товары для оформления заказа.',
            ], 400);
        }

        // Расчёт общей стоимости
        $totalCost = $cartProducts->sum(function ($item) {
            return $item->productColorSize->product->price * $item->quantity;
        });

        try {
            // 1. Сначала создаем платеж в Stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $totalCost * 100, // сумма в центах
                'currency' => 'rub',
                'payment_method_types' => ['card'],
                'description' => 'Тестовая оплата заказа',
                'metadata' => [
                    'user_id' => $clientId,
                    'test_payment' => 'true' // пометка тестового платежа
                ],
            ]);

            // 2. Если платеж создан успешно, создаем заказ
            $order = Order::create([
                'user_id' => $clientId,
                'point_id' => $addressId,
                'order_date' => now(),
                'total' => $totalCost,
                'status_id' => 1, // "Оплачен" (предполагая, что 2 - это статус оплаченного заказа)
                'payment_id' => $paymentIntent->id,
                'payment_status' => 'succeeded',
            ]);

            // 3. Создание записей для OrderItem
            $orderItems = $cartProducts->map(function ($item) use ($order) {
                return [
                    'order_id' => $order->id,
                    'product_color_size_id' => $item->product_color_size_id,
                    'quantity' => $item->quantity,
                    'total' => $item->productColorSize->product->price * $item->quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });

            OrderItem::insert($orderItems->toArray());

            // 4. Очистка корзины
            Cart::where('user_id', $clientId)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Заказ успешно оформлен и оплачен (тестовый платеж).',
                'order_id' => $order->id,
                'payment_id' => $paymentIntent->id,
                'payment_status' => 'succeeded',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ошибка при создании заказа: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        $isAdmin = $user->role->code === 'admin';

        // Загружаем заказ с вложенными связями
        $order = Order::with([
            'user',
            'point',
            'status',
            'orderItems.productColorSize.product',
            'orderItems.productColorSize.color',
            'orderItems.productColorSize.size'
        ])
            ->when(!$isAdmin, function ($query) use ($user) {
                // Ограничиваем по пользователю, если не админ
                $query->where('user_id', $user->id);
            })
            ->find($id);

        if (!$order) {
            return response()->json(['error' => 'Заказ не найден'], 404);
        }

        // Список товаров
        $items = $order->orderItems->map(function ($item) {
            $pcs = $item->productColorSize;
            $product = $pcs->product;

            return [
                'product_id' => $product->id,
                'photo' => $product->photo,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $item->quantity,
                'color' => $pcs->color->name ?? null,
                'size' => $pcs->size->name ?? null,
                'total' => round($product->price * $item->quantity, 2),
            ];
        });

        $totalCost = $items->sum('total');

        return response()->json([
            'id' => $order->id,
            'order_date' => $order->order_date,
            'status' => $order->status->name ?? null,
            'total_cost' => $totalCost,
            'user' => $isAdmin ? $order->user->name : null,
            'address' => [
                'city' => $order->point->city ?? null,
                'street' => $order->point->street ?? null,
                'house' => $order->point->house ?? null,
            ],
            'items' => $items,
        ]);
    }

    // Обновление заказа (например, обновление адреса или статуса)
    public function update(Request $request, $id)
    {
        if (Auth::user()->role->code != 'admin' && 'manager') {
            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }

        // Загружаем заказ
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Заказ не найден'], 404);
        }

        // Валидируем данные
        $validated = $request->validate([
            'status_id' => 'nullable|integer',
        ]);

        $order->update($validated);

        return response()->json([
            'message' => 'Заказ обновлён',
            'order' => $order,
        ]);
    }

    // Отмена заказа
    public function destroy($id)
    {
        $user = auth()->user();

        // Находим заказ текущего пользователя
        $order = $user->orders()->find($id);

        if (!$order) {
            return response()->json(['error' => 'Заказ не найден'], 404);
        }

        $order->status_id = 4;
        $order->save();

        return response()->json(['message' => 'Заказ отменён']);
    }
}
