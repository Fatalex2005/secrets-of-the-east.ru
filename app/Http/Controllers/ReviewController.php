<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\ReviewsRequests\CreateReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController
{
    public function index($id) {
        $product = Product::find($id);
        if (!$product) {
            throw new ApiException('Товар не найден', 404);
        }

        $reviews = Review::with(['user' => function($query) {
            $query->select('id', 'name');
        }])
            ->where('product_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($reviews->isEmpty()) {
            throw new ApiException('Отзывы для данного товара не указаны', 404);
        }

        return response()->json($reviews);
    }
    public function store(CreateReviewRequest $request, $id) {
        if(Auth::user()->role->code != 'user'){

            return response()->json(['message' => 'Только обычные пользователи могут оставлять отзывы'], 403);
        }

        // Создаём отзыв
        $review = Review::create([
            'rating' => $request->rating,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
            'product_id' => $id,
        ]);

        return response()->json($review, 201);
    }
    public function destroy($productId, $reviewId)
    {
        $user = Auth::user();
        $product = Product::find($productId);
        $review = Review::find($reviewId);

        // Проверка существования товара и отзыва
        if (!$product) {
            throw new ApiException('Товар не найден', 404);
        }
        if (!$review) {
            throw new ApiException('Отзыв не найден', 404);
        }

        // Проверка соответствия отзыва и товара
        if ($review->product_id != $product->id) {
            throw new ApiException('Этот отзыв не принадлежит указанному товару', 400);
        }

        // Проверка прав доступа
        $isAdminOrManager = in_array($user->role->code, ['admin', 'manager']);
        $isReviewAuthor = $review->user_id == $user->id;

        if (!$isAdminOrManager && !$isReviewAuthor) {
            return response()->json([
                'message' => 'Недостаточно прав для удаления отзыва'
            ], 403);
        }

        // Удаление отзыва
        $review->delete();

        return response()->json([
            'message' => 'Отзыв успешно удален'
        ]);
    }
}
