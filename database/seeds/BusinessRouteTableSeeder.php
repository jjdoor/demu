<?php

use Illuminate\Database\Seeder;

class BusinessRouteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("business_route")->truncate();
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            \App\BusinessPort::create([
                'route_id' => rand(1, 50),
                'segment_business_id' => rand(1, 50),
                'master_business_id' => rand(1, 50),
                'slaver_business_id' => rand(1, 50),
            ]);
        }
    }
}
