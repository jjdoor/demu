<?php

use Illuminate\Database\Seeder;

class ContainerAddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("container_addresses")->truncate();
        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\ContainerAddress::create([
                'address'=>$faker->address,
                'is_up'=>$faker->boolean,
                'is_down'=>$faker->boolean,
                'status'=>rand(0,1),
            ]);
        }
    }
}
