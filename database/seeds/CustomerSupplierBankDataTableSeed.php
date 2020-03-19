<?php

use Illuminate\Database\Seeder;

class CustomerSupplierBankDataTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("customer_supplier_bank_data")->truncate();
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            \App\CustomerSupplierBankData::create([
                'name' => $faker->word,
                'account' => $faker->word,
                'customer_supplier_id' => rand(1, 50),
                'currency' => call_user_func(function () {
                    $arr = ['USD', 'CNY'];
                    $array_rand = array_rand($arr, 1);
                    return $arr[$array_rand];
                }),
            ]);
        }
    }
}
