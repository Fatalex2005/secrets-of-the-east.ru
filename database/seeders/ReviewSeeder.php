<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::create([
            'rating' => 5,
            'description' => 'Идеальная куртка! Смотрится дороже своей цены, все швы аккуратные. Носила всю зиму — не промокает.',
            'user_id' => 3,
            'product_id' => 1,
        ]);

        Review::create([
            'rating' => 4,
            'description' => 'Хорошие джинсы, но после стирки сели на полразмера. Советую брать на размер больше.',
            'user_id' => 7,
            'product_id' => 1,
        ]);

        Review::create([
            'rating' => 2,
            'description' => 'Футболка выцвела после второй стирки. Принт потрескался. Очень разочарован.',
            'user_id' => 4,
            'product_id' => 1,
        ]);

        Review::create([
            'rating' => 5,
            'description' => 'Платье сидит как влитое! Ткань приятная, не мнётся. Фото полностью соответствуют реальности.',
            'user_id' => 10,
            'product_id' => 1,
        ]);

        Review::create([
            'rating' => 3,
            'description' => 'Шапка тёплая, но сильно линяет. Пришлось стирать отдельно, чтобы не испортить другие вещи.',
            'user_id' => 5,
            'product_id' => 1,
        ]);

        Review::create([
            'rating' => 5,
            'description' => 'Лучшие спортивные легинсы! Не сползают при беге, отводят влагу. Уже вторую пару беру.',
            'user_id' => 9,
            'product_id' => 1,
        ]);

        Review::create([
            'rating' => 1,
            'description' => 'Рубашка пришла с кривыми строчками и торчащими нитками. Качество ужасное за такие деньги.',
            'user_id' => 6,
            'product_id' => 1,
        ]);

        Review::create([
            'rating' => 4,
            'description' => 'Удобное худи, но молния иногда заедает. В целом доволен, но можно доработать фурнитуру.',
            'user_id' => 7,
            'product_id' => 1,
        ]);

        Review::create([
            'rating' => 5,
            'description' => 'Купальник просто огонь! Отлично держит форму, не выгорает на солнце. Рекомендую!',
            'user_id' => 8,
            'product_id' => 1,
        ]);

        Review::create([
            'rating' => 3,
            'description' => 'Брюки хорошие, но ткань слишком маркая. Пришлось вернуть, так как белый цвет быстро пачкается.',
            'user_id' => 9,
            'product_id' => 1,
        ]);
    }
}
