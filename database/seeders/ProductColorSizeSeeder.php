<?php

namespace Database\Seeders;

use App\Models\ProductColorSize;
use Illuminate\Database\Seeder;

class ProductColorSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Генерируем 60 товаров с разными цветами и размерами
        for ($productId = 1; $productId <= 60; $productId++) {
            // Случайное количество цветов для товара (от 1 до 4)
            $colorsCount = rand(1, 4);
            $usedColors = [];

            // Назначаем товару несколько цветов
            for ($i = 0; $i < $colorsCount; $i++) {
                // Выбираем случайный цвет (1-10), который еще не использован для этого товара
                do {
                    $colorId = rand(1, 10);
                } while (in_array($colorId, $usedColors));

                $usedColors[] = $colorId;

                // Случайное количество размеров для данного цвета (от 1 до 6)
                $sizesCount = rand(1, 6);
                $usedSizes = [];

                // Назначаем размеры для цвета
                for ($j = 0; $j < $sizesCount; $j++) {
                    // Выбираем случайный размер (1-6), который еще не использован для этого цвета
                    do {
                        $sizeId = rand(1, 6);
                    } while (in_array($sizeId, $usedSizes));

                    $usedSizes[] = $sizeId;

                    // Создаем запись в ProductColorSize
                    ProductColorSize::create([
                        'quantity' => rand(1, 20), // Случайное количество на складе
                        'product_id' => $productId,
                        'color_id' => $colorId,
                        'size_id' => $sizeId,
                    ]);
                }
            }
        }
    }
}
