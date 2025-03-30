<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'photo' => null,
            'name' => 'Хорошая шапка',
            'description' => 'Очень тёплая шапочка на новый год',
            'sex' => 1,
            'quantity' => 8,
            'price' => '2400',
            'category_id' => 1,
            'country_id' => 1,
        ]);

        Product::create([
            'photo' => null,
            'name' => 'Хорошее худи',
            'description' => 'Очень тёплое худи на новый год',
            'sex' => 1,
            'quantity' => 10,
            'price' => '2400',
            'category_id' => 2,
            'country_id' => 2,
        ]);
    }
}
