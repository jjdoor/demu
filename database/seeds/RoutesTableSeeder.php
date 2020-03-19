<?php

use Illuminate\Database\Seeder;

class RoutesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("routes")->truncate();
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            \App\Route::query()->create([
                'name' => $faker->word,
                'user_id' => rand(1, 50),
                'status' => rand(0, 1),
            ]);
        }
    }
}
