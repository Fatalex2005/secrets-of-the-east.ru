<?php

namespace Database\Seeders;

use App\Models\ProductColorSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductColorSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductColorSize::create([
            'quantity' => 8,
            'product_id' => 1,
            'color_id' => 1,
            'size_id' => 1,
        ]);
        ProductColorSize::create([
            'quantity' => 10,
            'product_id' => 2,
            'color_id' => 2,
            'size_id' => 2,
        ]);
    }
}
