<?php

use Illuminate\Database\Seeder;

class CustomerSupplierShipDataDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("customer_supplier_ship_data_data")->truncate();
//        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\CustomerSupplierShipDataData::create([
                'customer_supplier_ship_data_id'=>rand(1,50),
                'segment_business_id'=>rand(1,50),
                'master_business_id'=>rand(1,50),
                'slaver_business_id'=>rand(1,50),
                'user_id'=>rand(1,50),
            ]);
        }
    }
}
