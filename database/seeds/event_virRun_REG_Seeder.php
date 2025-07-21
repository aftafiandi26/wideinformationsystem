<?php

use Illuminate\Database\Seeder;

class event_virRun_REG_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $data = [
            'user_id'   => 226,
            'ebib'      => $faker->unique()->numberBetween(25001, 25100),
            'active'    => true,
            'email'     => $faker->safeEmail()
        ];

        for ($i=0; $i <= 10 ; $i++) { 
            
        }
    }
}
