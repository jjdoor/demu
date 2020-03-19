<?php

use Illuminate\Database\Seeder;

class CompanyOrganizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table("company_organizes")->truncate();
        $faker = \Faker\Factory::create();
        for ($i=0;$i<50;$i++){
            \App\CompanyOrganize::create([
                'name'=>$faker->word,
//                'parent_id'=>\Illuminate\Support\Str::random(1),
                'parent_id'=>rand(1,10),
                'status'=>rand(0,1),
            ]);
        }
    }
}
