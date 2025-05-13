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
            'name' => 'Администратор Системный',
            'email' => 'admin@mail.ru',
            'telephone' => '88005553501',
            'sex' => 1,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'Менеджер Отдела',
            'email' => 'manager@mail.ru',
            'telephone' => '88005553502',
            'sex' => 1,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'Иванов Иван Иванович',
            'email' => 'ivanov@mail.ru',
            'telephone' => '88005553503',
            'sex' => 1,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 3,
        ]);

        User::create([
            'name' => 'Петрова Мария Сергеевна',
            'email' => 'petrova@mail.ru',
            'telephone' => '88005553504',
            'sex' => 0,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 3,
        ]);

        User::create([
            'name' => 'Сидоров Алексей Дмитриевич',
            'email' => 'sidorov@mail.ru',
            'telephone' => '88005553505',
            'sex' => 1,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 3,
        ]);

        User::create([
            'name' => 'Кузнецова Елена Викторовна',
            'email' => 'kuznetsova@mail.ru',
            'telephone' => '88005553506',
            'sex' => 0,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 3,
        ]);

        User::create([
            'name' => 'Смирнов Денис Олегович',
            'email' => 'smirnov@mail.ru',
            'telephone' => '88005553507',
            'sex' => 1,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 3,
        ]);

        User::create([
            'name' => 'Федорова Ольга Игоревна',
            'email' => 'fedorova@mail.ru',
            'telephone' => '88005553508',
            'sex' => 0,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 3,
        ]);

        User::create([
            'name' => 'Абдул Абдурихманович',
            'email' => 'abdul@mail.ru',
            'telephone' => '88005553509',
            'sex' => 1,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 3,
        ]);

        User::create([
            'name' => 'Алексей Петрович',
            'email' => 'ruginpetr@mail.ru',
            'telephone' => '88005553510',
            'sex' => 1,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 3,
        ]);

        User::create([
            'name' => 'Новикова Анна Михайловна',
            'email' => 'novikova@mail.ru',
            'telephone' => '88005553511',
            'sex' => 0,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 3,
        ]);

        User::create([
            'name' => 'Козлов Артем Владимирович',
            'email' => 'kozlov@mail.ru',
            'telephone' => '88005553512',
            'sex' => 1,
            'password' => 'Password123@',
            'api_token' => null,
            'role_id' => 3,
        ]);
    }
}
