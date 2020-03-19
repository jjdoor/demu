<?php

use Illuminate\Database\Seeder;

class ShipCompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("ship_companies")->truncate();
        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\ShipCompany::query()->create([
                'parent_id'=>rand(1,50),
                'name'=>$faker->word,
                'status'=>rand(0,1),
            ]);
        }
    }
}
