<?php

use Illuminate\Database\Seeder;

class CustomerSupplierShipDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("customer_supplier_ship_data")->truncate();
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            \App\CustomerSupplierShipData::create([
                'customer_supplier_id' => rand(1, 50),
                'name' => $faker->word,
                'user_id' => rand(1, 50),
                'status' => rand(0, 1),
            ]);
        }
    }
}
