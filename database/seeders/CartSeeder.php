<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cart::create([
            'total' => 7600,
            'quantity' => 4,
            'user_id' => 1,
            'product_color_size_id' => 1,
        ]);

        Cart::create([
            'total' => 10000,
            'quantity' => 5,
            'user_id' => 2,
            'product_color_size_id' => 2,
        ]);
    }
}
