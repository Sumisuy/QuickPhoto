<?php

use Illuminate\Database\Seeder;

class AddUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Martin Smith',
            'email' => 'sumisuy@gmail.com',
            'password' => bcrypt('password'),
        ]);
    }
}
