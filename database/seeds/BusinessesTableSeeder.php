<?php

use Illuminate\Database\Seeder;

class BusinessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table("businesses")->truncate();
        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\Business::create([
                'name'=>$faker->word,
                'parent_id'=>rand(1,3),
//                'parent_id'=>\Illuminate\Support\Str::random(1),
                'status'=>rand(0,1),
            ]);
        }
    }
}
