<?php

use Illuminate\Database\Seeder;

class ContractCustomerSupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("contract_customer_supplier")->truncate();
//        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\ContractCustomerSupplier::create([
                'contract_id'=>rand(1,50),
                'customer_supplier_id'=>rand(1,50),
                'is_invoice'=>rand(0,1),
            ]);
        }
    }
}
