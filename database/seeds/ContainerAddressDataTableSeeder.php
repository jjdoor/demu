<?php

use Illuminate\Database\Seeder;

class ContainerAddressDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("container_address_data")->truncate();
//        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\ContainerAddressData::create([
                'segment_business_id'=>rand(1,50),
                'master_business_id'=>rand(1,50),
                'slaver_business_id'=>rand(1,50),
                'container_address_id'=>rand(1,50),
            ]);
        }
    }
}
