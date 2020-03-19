<?php

use Illuminate\Database\Seeder;

class FreightForwardersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /** @noinspection PhpUndefinedClassInspection */
        \Illuminate\Support\Facades\DB::table("freight_forwarders")->truncate();
        $faker = \Faker\Factory::create();
        $form = ['person','company'];
        for ($i=0;$i<50;$i++){
            \App\FreightForwarder::create([
                'sn'=>$faker->word,
                'from'=>$form[array_rand($form)],
                'segment_business_id'=>rand(1,50),
                'master_business_id'=>rand(1,50),
                'slaver_business_id'=>rand(1,50),
                'customer_supplier_id'=>rand(1,50),
                'clear_company_id'=>rand(1,50),
                'created_user_id'=>rand(1,50),
                'created_user_name'=>$faker->word,
                'created_created_at'=>\Carbon\Carbon::now()->format("Y-m-d H:i:s"),
                'service_user_id'=>rand(1,50),
                'service_user_name'=>$faker->word,
                'sale_user_id'=>rand(1,50),
                'sale_user_name'=>$faker->word,
            ]);
        }
    }
}
