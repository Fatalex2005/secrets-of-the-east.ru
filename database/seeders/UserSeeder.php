<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Абдул Абдурихманович',
            'email' => 'abdul@mail.ru',
            'telephone' => '88005553535',
            'sex' => 1,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 1,
        ]);
        User::create([
            'name' => 'Алексей Петрович',
            'email' => 'ruginpetr@mail.ru',
            'telephone' => '88005553536',
            'sex' => 1,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 1,
        ]);
    }
}
