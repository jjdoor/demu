<?php

use Illuminate\Database\Seeder;

class ChargeItemTaxRatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("charge_item_tax_rates")->truncate();
        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\ChargeItemTaxRate::create([
                'segment_business_id'=>rand(0,50),
                'master_business_id'=>rand(1,50),
                'slaver_business_id'=>rand(2,50),
                'charge_item_id'=>rand(1,50),
                'invoice_type_id'=>rand(1,50),
                'is_tax_free'=>rand(0,1),
            ]);
        }
    }
}
