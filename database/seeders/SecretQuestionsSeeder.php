<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class SecretQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $faker = Faker::create();


       for ($i=0; $i < 20; $i++) { 
          DB::table('secret_questions')->insert(
            [
                'name' => $faker->word
            ]
          );
       }
    }
}
