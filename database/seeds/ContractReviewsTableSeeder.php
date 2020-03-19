<?php

use Illuminate\Database\Seeder;

class ContractReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("contracts")->truncate();
        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\Contract::create([
//                'sn'=>$faker->bankAccountNumber,
//                'customer_sn'=>$faker->bankAccountNumber,
//                'name'=>$faker->word,
//                'clear_companies_id'=>rand(1,50),
//                'businesses_id_string'=>rand(1,50).",".rand(1,50),
//                'is_invoice'=>rand(0,1),
//                'part_a_clear_companies_id'=>rand(1,50),
//                'part_b_clear_companies_id'=>rand(1,50),
//                'part_c_clear_companies_id'=>rand(1,50),
//                'charge_rules_id_string'=>rand(1,50).",".rand(1,50),
//                'applicant_users_id'=>rand(1,50),
            ]);
        }    }
}
