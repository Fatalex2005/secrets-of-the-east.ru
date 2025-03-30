<?php

namespace Database\Seeders;

use App\Models\Point;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Point::create([
            'city' => 'Томск',
            'street' => 'Иркутский тракт',
            'house' => '175',
        ]);
        Point::create([
            'city' => 'Томск',
            'street' => 'Иркутский тракт',
            'house' => '173',
        ]);
    }
}
