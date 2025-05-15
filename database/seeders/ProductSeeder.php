<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            1 => 'Верхняя одежда',
            2 => 'Свитшоты и худи',
            3 => 'Футболки и топы',
            4 => 'Рубашки и блузы',
            5 => 'Джинсы и брюки',
            6 => 'Спортивная одежда',
        ];

        $photos = [
            'storage/products/girl1.jpg',
            'storage/products/girl2.jpg',
            'storage/products/girl3.jpg',
            'storage/products/girl4.jpg',
            'storage/products/girl5.jpg',
            'storage/products/girl6.jpg',
            'storage/products/girl7.jpg',
            'storage/products/girl8.jpg',
            'storage/products/girl9.jpg',
            'storage/products/girl10.jpg',
            'storage/products/girl11.jpg',
            'storage/products/girl12.jpg',
            'storage/products/girl13.jpg',
            'storage/products/girl14.jpg',
            'storage/products/girl15.jpg',
            'storage/products/girl16.jpg',
            'storage/products/girl17.jpg',
        ];

        for ($i = 1; $i <= 60; $i++) {
            $categoryId = rand(1, 6);
            $randomPhoto = $photos[array_rand($photos)];

            Product::create([
                'photo' => url($randomPhoto),
                'name' => $categories[$categoryId] . ' ' . $i,
                'description' => 'Описание товара',
                'sex' => rand(0, 1),
                'quantity' => rand(5, 15),
                'price' => rand(1000, 5000),
                'category_id' => $categoryId,
                'country_id' => rand(1, 3),
            ]);
        }
    }
}
