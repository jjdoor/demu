<?php

use Illuminate\Database\Seeder;

class ShipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("ships")->truncate();
        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\Ship::create([
                'parent_id'=>rand(1,50),
                'name'=>$faker->word,
                'status'=>rand(0,1),
            ]);
        }
    }
}
