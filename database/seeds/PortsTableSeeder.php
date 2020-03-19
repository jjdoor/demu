<?php

use Illuminate\Database\Seeder;

class PortsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("ports")->truncate();
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            \App\Port::create([
                'name' => $faker->word,
                'name_code' => $faker->word,
                'country' => $faker->sentence,
//                'address' => $faker->sentence,
//                'parent_id'=>\Illuminate\Support\Str::random(1),
                'status' => rand(0, 1),
                'user_id' => rand(1, 50)
            ]);
        }
    }
}
