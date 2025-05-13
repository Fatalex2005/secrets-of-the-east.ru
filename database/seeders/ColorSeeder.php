<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Color::create([
            'name' => 'Чёрный',
            'hex' => '#000000',
        ]);

        Color::create([
            'name' => 'Белый',
            'hex' => '#FFFFFF',
        ]);

        Color::create([
            'name' => 'Красный',
            'hex' => '#FF0000',
        ]);

        Color::create([
            'name' => 'Зелёный',
            'hex' => '#00FF00',
        ]);

        Color::create([
            'name' => 'Синий',
            'hex' => '#0000FF',
        ]);

        Color::create([
            'name' => 'Жёлтый',
            'hex' => '#FFFF00',
        ]);

        Color::create([
            'name' => 'Фиолетовый',
            'hex' => '#800080',
        ]);

        Color::create([
            'name' => 'Голубой',
            'hex' => '#00FFFF',
        ]);

        Color::create([
            'name' => 'Розовый',
            'hex' => '#FFC0CB',
        ]);

        Color::create([
            'name' => 'Оранжевый',
            'hex' => '#FFA500',
        ]);
    }
}
