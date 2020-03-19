<?php

use Illuminate\Database\Seeder;

class ContractsTableSeeder extends Seeder
{
    public function run(){
        DB::table("contracts")->truncate();
        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\Contract::create([
                'name'=>$faker->word,
                'sn'=>$faker->creditCardNumber,
                'sn_alias'=>$faker->creditCardNumber,
                'type'=>['customer','supplier'][array_rand(['customer','supplier'],1)],
                'clear_company_id'=>rand(1,50),
                'process0_user_id'=>rand(1,50),
            ]);
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run_bak()
    {
        DB::table("contracts")->truncate();
        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\Contract::create([
                'sn'=>$faker->bankAccountNumber,
                'customer_sn'=>$faker->bankAccountNumber,
                'name'=>$faker->word,
                'type'=>'customer',
                'clear_company_id'=>rand(1,50),
                'is_invoice'=>rand(0,1),
                'part_a_customer_supplier_id'=>rand(1,50),
                'part_b_customer_supplier_id'=>rand(1,50),
                'part_c_customer_supplier_id'=>rand(1,50),
                'from'=>'company',
                'attachment'=>'http://www.baidu.com/'.rand(1,10000).'.pdf',
                'process0_user_id'=>rand(1,50),
                'process0_status'=>null,
                'process0_time'=>date('Y-m-d H:i:s'),
                'process1_user_id'=>rand(1,50),
                'process1_status'=>null,
                'process1_time'=>date('Y-m-d H:i:s'),
                'process2_user_id'=>rand(1,50),
                'process2_status'=>null,
                'process2_time'=>date('Y-m-d H:i:s'),
                'process3_user_id'=>rand(1,50),
                'process3_status'=>null,
                'process3_time'=>date('Y-m-d H:i:s'),
                'process4_user_id'=>rand(1,50),
                'process4_status'=>null,
                'process4_time'=>date('Y-m-d H:i:s'),
            ]);
        }
    }
}
