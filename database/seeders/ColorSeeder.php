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
    }
}
