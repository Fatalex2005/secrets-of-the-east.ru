<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class OrderController
{
    // Получение списка всех заказов пользователя
    public function index()
    {
        try {
            $user = auth()->user();
            $isAdmin = $user->role->code === 'admin' || $user->role->code === 'manager';

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

            // Если заказов нет - возвращаем 404
            if ($orders->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $isAdmin
                        ? 'В системе пока нет заказов'
                        : 'У вас пока нет заказов'
                ], 404);
            }

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
                    ],
                    'items_count' => $order->orderItems->count()
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $ordersTransformed
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Произошла ошибка при получении списка заказов'
            ], 500);
        }
    }

    // Создание нового заказа с тестовой оплатой
    public function store(Request $request)
    {
        $clientId = $request->user()->id;
        $addressId = $request->input('point_id');

        // Проверка наличия адреса
        if (!$addressId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Не указан адрес доставки.',
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
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // 1. Создаем сессию оплаты Stripe Checkout
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'rub',
                        'product_data' => [
                            'name' => 'Заказ от ' . $request->user()->name,
                        ],
                        'unit_amount' => $totalCost * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
                'metadata' => [
                    'user_id' => $clientId,
                    'address_id' => $addressId,
                    // Передаем только необходимые минимальные данные
                    'cart_summary' => json_encode([
                        'items_count' => $cartProducts->count(),
                        'total' => $totalCost
                    ])
                ],
            ]);

            // 2. Возвращаем URL для перенаправления на страницу оплаты Stripe
            return response()->json([
                'status' => 'payment_required',
                'message' => 'Требуется подтверждение оплаты',
                'payment_url' => $session->url,
                'session_id' => $session->id
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ошибка при создании сессии оплаты: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function handleSuccess(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $sessionId = $request->get('session_id');
            $session = Session::retrieve($sessionId);

            // Получаем данные из metadata
            $clientId = $session->metadata->user_id;
            $addressId = $session->metadata->address_id;

            // 1. Получаем корзину пользователя из базы данных
            $cartProducts = Cart::where('user_id', $clientId)
                ->with('productColorSize.product')
                ->get();

            if ($cartProducts->isEmpty()) {
                throw new \Exception('Корзина пуста или уже обработана');
            }

            // 2. Создаем заказ
            $order = Order::create([
                'user_id' => $clientId,
                'point_id' => $addressId,
                'order_date' => now(),
                'total' => $session->amount_total / 100,
                'status_id' => 2,
                'payment_id' => $session->payment_intent,
                'payment_status' => 'succeeded',
            ]);

            // 3. Добавляем товары в заказ
            foreach ($cartProducts as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_color_size_id' => $item->product_color_size_id,
                    'quantity' => $item->quantity,
                    'total' => $item->productColorSize->product->price * $item->quantity,
                ]);
            }

            // 4. Очищаем корзину
            Cart::where('user_id', $clientId)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Заказ успешно оформлен и оплачен',
                'order_id' => $order->id,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ошибка при обработке платежа: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function handleCancel(Request $request)
    {
        return response()->json([
            'status' => 'canceled',
            'message' => 'Оплата отменена',
        ], 200);
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

    // Обновление заказа (статус)
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
