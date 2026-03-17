<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('users')->insert([
            [
                'name' => 'Ilham',
                'email' => 'ilham@example.com',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'Arifin',
                'email' => 'arifin@example.com',
                'password' => bcrypt('password')
            ]
        ]);
    }
}
