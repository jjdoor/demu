<?php

use Illuminate\Database\Seeder;

class CustomerSuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("customer_suppliers")->truncate();
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            \App\CustomerSupplier::create([
                'name' => $faker->word,
                'name_abbreviation' => $faker->word,
                'name_code' => $faker->word,
                'tax_identification_number' => $faker->word,
                'contact' => $faker->word,
                'id_card_number' => rand(100000, 999999),
                'tel_area_code' => rand(1000, 9999),
                'tel' => rand(1300000, 1400000),
                'mobile' => rand(1300000, 1400000),
                'country_id' => 1,
                'city_id' => rand(1, 1000),
                'address' => $faker->address,
                'email' => $faker->email,
                'logistics_role' => rand(1, 18),
                'is_customer' => rand(0, 1),
                'is_supplier' => rand(0, 1),
                'is_invoice' => rand(0, 1),
                'is_self' => rand(0, 1),
                'pay_max_time' => 15 * rand(1, 4),
                'receive_max_time' => 15 * rand(1, 4),
                'credit_max_money' => rand(10000, 9999),
                'credit_max_time' => rand(1, 45),
                'created_user_id' => rand(1, 50),
                'created_user_name' => $faker->word,
                'created_time' => \Carbon\Carbon::now()->format("Y-m-d H:i:s"),
                'updated_user_id' => rand(1, 50),
                'updated_user_name' => $faker->word,
                'updated_time' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'lock_user_id' => rand(1, 50),
                'lock_user_name' => $faker->word,
                'lock_time' => date('Y-m-d H:i:s'),
                'is_lock' => rand(0, 1)
            ]);
        }
    }
}
