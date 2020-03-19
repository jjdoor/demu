<?php

use Illuminate\Database\Seeder;

class InvoiceTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("invoice_types")->truncate();
        $faker = \Faker\Factory::create();
        $direct = ['in','out'];
        for ($i=0;$i<50;$i++){
            \App\InvoiceType::create([
                'direction'=>$direct[rand(0,1)],
                'name'=>$faker->word,
                'tax_rate'=>rand(0,17),
                'user_id'=>rand(1,50),
            ]);
        }
    }
}
