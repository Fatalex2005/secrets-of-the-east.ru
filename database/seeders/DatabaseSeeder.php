<?php

namespace Database\Seeders;

use App\Models\ProductColorSize;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            StatusSeeder::class,
            PointSeeder::class,
            CategorySeeder::class,
            CountrySeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
            OrderSeeder::class,
            ProductColorSize::class,
            CartSeeder::class,
            OrderItemSeeder::class,
        ]);
    }
}
