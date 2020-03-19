<?php

use Illuminate\Database\Seeder;

class ChargeItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("charge_items")->truncate();
        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\ChargeItem::create([
                'code'=>$faker->word,
                'name'=>$faker->word,
            ]);
        }
    }
}
