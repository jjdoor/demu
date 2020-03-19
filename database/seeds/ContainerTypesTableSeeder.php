<?php

use Illuminate\Database\Seeder;

class ContainerTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("container_types")->truncate();
        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\ContainerType::create([
                'name'=>$faker->word,
                'size'=>$faker->word,
            ]);
        }
    }
}
