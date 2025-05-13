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

        Point::create([
            'city' => 'Москва',
            'street' => 'Тверская',
            'house' => '10',
        ]);

        Point::create([
            'city' => 'Санкт-Петербург',
            'street' => 'Невский проспект',
            'house' => '28',
        ]);

        Point::create([
            'city' => 'Новосибирск',
            'street' => 'Красный проспект',
            'house' => '50',
        ]);

        Point::create([
            'city' => 'Екатеринбург',
            'street' => 'Ленина',
            'house' => '24а',
        ]);

        Point::create([
            'city' => 'Казань',
            'street' => 'Баумана',
            'house' => '37',
        ]);

        Point::create([
            'city' => 'Сочи',
            'street' => 'Курортный проспект',
            'house' => '103',
        ]);

        Point::create([
            'city' => 'Владивосток',
            'street' => 'Светланская',
            'house' => '69',
        ]);

        Point::create([
            'city' => 'Калининград',
            'street' => 'Ленинский проспект',
            'house' => '30',
        ]);
    }
}
