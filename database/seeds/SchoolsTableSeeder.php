<?php

use Illuminate\Database\Seeder;

class SchoolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('schools')->insert([
           'name' => 'Kent County High School',
           'code' => str_random(60),
           'data' => json_encode([]),
           'activated' => false
       ]);

       DB::table('schools')->insert([
          'name' => 'Queen Annes County High School',
          'code' => str_random(60),
          'data' => json_encode([]),
          'activated' => true
      ]);

    }
}
