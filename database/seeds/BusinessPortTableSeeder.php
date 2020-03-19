<?php

use Illuminate\Database\Seeder;

class BusinessPortTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("business_port")->truncate();
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            \App\BusinessPort::create([
                'port_id' => rand(1, 50),
                'segment_business_id' => rand(1, 50),
                'master_business_id' => rand(1, 50),
                'slaver_business_id' => rand(1, 50),
            ]);
        }
    }
}
