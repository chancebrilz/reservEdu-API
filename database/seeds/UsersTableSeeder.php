<?php

use Illuminate\Database\Seeder;

use Illuminate\Hashing\BcryptHasher as Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('users')->insert([
           'name' => 'Chance Brilz',
           'email' => 'chancebrilz@gmail.com',
           'username' => 'chance',
           'password' => app('hash')->make('password'),
           'api_token' => str_random(60),
           'permissions' => '{}'
       ]);
    }
}
