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
    // Метод для просмотра конкретных бонусов
    public function show($id)
    {
        $product = Product::with('color')->find($id);
        if ($product) {
            return response()->json($product)->setStatusCode(200, 'Успешно');
        } else {
            return response()->json('Бонус не найден')->setStatusCode(404, 'Не найдено');
        }
    }
    // Метод для обновления бонусов
    public function update(UpdateProductRequest $request, $id)
    {
        if(Auth::user()->role->code != 'admin'){

            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }
        $product = Product::find($id);
        if ($product){
            $product->update($request->all());
            return response()->json($product)->setStatusCode(200, 'Успешно');
        }else{
            return response()->json('Бонус не найден')->setStatusCode(404, 'Не найдено');
        }
    }
    // Метод для удаления бонусов
    public function destroy($id)
    {
        if(Auth::user()->role->code != 'admin'){

            return response()->json(['message' => 'У вас нет прав на выполнение этого действия'], 403);
        }
        $product = Product::find($id);
        if (!$product){
            throw new ApiException('Не найдено', 404);
        }
        $product->delete();
        return response()->json('Товар удален')->setStatusCode(200);
    }
}
