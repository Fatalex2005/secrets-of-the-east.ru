<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Верхняя одежда']);       // Пальто, пуховики, куртки
        Category::create(['name' => 'Свитшоты и худи']);     // Худи, оверсайз, с принтами
        Category::create(['name' => 'Футболки и топы']);     // Базовые, принтованные, поло
        Category::create(['name' => 'Рубашки и блузы']);     // Офисные, повседневные, шелковые
        Category::create(['name' => 'Джинсы и брюки']);     // Скинни, клеш, карго, классика
        Category::create(['name' => 'Спортивная одежда']);   // Леггинсы, спортивные костюмы
    }
}
