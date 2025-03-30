<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            'order_date' => '2025-03-30 08:00:00',
            'total' => 7600,
            'user_id' => 1,
            'status_id' => 1,
            'point_id' => 1,
        ]);

        Order::create([
            'order_date' => '2025-03-30 08:00:00',
            'total' => 10000,
            'user_id' => 2,
            'status_id' => 1,
            'point_id' => 1,
        ]);
    }
}
