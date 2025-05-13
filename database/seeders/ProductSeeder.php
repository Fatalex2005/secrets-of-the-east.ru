<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [1 => 'Шапка', 2 => 'Худи', 3 => 'Штаны'];

        for ($i = 1; $i <= 60; $i++) {
            $categoryId = rand(1, 3);

            Product::create([
                'photo' => url('storage/products/girl1.jpg'),
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
