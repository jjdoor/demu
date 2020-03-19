<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("products")->truncate();
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            \App\Product::create([
                'name' => $faker->word,
                'user_id' => rand(1, 50),
//                'parent_id'=>\Illuminate\Support\Str::random(1),
                'status' => rand(0, 1),
            ]);
        }
    }
}
