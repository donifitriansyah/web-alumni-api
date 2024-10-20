<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => 'admin1',
                'email' => 'admin1@mail.com',
                'password' => Hash::make('password123'), // Use a hashed password
                'role' => 'admin',
                'created_at' => Carbon::now(),
            ],
            [
                'username' => 'admin2',
                'email' => 'admin2@mail.com',
                'password' => Hash::make('password123'), // Use a hashed password
                'role' => 'admin',
                'created_at' => Carbon::now(),
            ],
            [
                'username' => 'Septian',
                'email' => 'septian@mail.com',
                'password' => Hash::make('password123'), // Use a hashed password
                'role' => 'alumni',
                'created_at' => Carbon::now(),
            ],
            [
                'username' => 'Ferry',
                'email' => 'ferry@mail.com',
                'password' => Hash::make('password123'), // Use a hashed password
                'role' => 'alumni',
                'created_at' => Carbon::now(),
            ],
            [
                'username' => 'ptmencaricintasejati',
                'email' => 'perusahaan1@mail.com',
                'password' => Hash::make('password123'), // Use a hashed password
                'role' => 'perusahaan',
                'created_at' => Carbon::now(),
            ],
            [
                'username' => 'terusberusahahaha',
                'email' => 'perusahaan2@mail.com',
                'password' => Hash::make('password123'), // Use a hashed password
                'role' => 'perusahaan',
                'created_at' => Carbon::now(),
            ],
            [
                'username' => 'capekpak',
                'email' => 'info@capek.com',
                'password' => Hash::make('password123'), // Use a hashed password
                'role' => 'perusahaan',
                'created_at' => Carbon::now(),
        ]
        ]);
    }
}
