<?php

use Illuminate\Database\Seeder;

class ClearCompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("clear_companies")->truncate();
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            \App\ClearCompany::create([
                'name' => $faker->word,
                'name_abbreviation' => $faker->word,
                'name_code' => $faker->word,
//                'parent_id'=>\Illuminate\Support\Str::random(1),
                'status' => rand(0, 1),
                'user_id' => rand(1, 50),
            ]);
        }
    }
}
