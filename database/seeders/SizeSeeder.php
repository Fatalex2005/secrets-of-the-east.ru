<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Size::create(['name' => '26-27']);
        Size::create(['name' => '28-29']);
        Size::create(['name' => '30-31']);
        Size::create(['name' => '32-33']);
        Size::create(['name' => '34-35']);
        Size::create(['name' => '36-37']);
        Size::create(['name' => '38-39']);
        Size::create(['name' => '40-41']);
        Size::create(['name' => '42-43']);
        Size::create(['name' => '44-45']);
    }
}
