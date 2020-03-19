<?php

use Illuminate\Database\Seeder;

class SwitchBillCompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("switch_bill_companies")->truncate();
        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\SwitchBillCompany::create([
                'name'=>$faker->word,
                'status'=>rand(0,1),
            ]);
        }
    }
}
