<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::create([
            'rating' => 4,
            'description' => 'Недостаточно тёплая как хотелось бы',
            'user_id' => 1,
            'product_id' => 1,
        ]);
        Review::create([
            'rating' => 5,
            'description' => 'Очень хорошо душу греет',
            'user_id' => 2,
            'product_id' => 2,
        ]);
    }
}
