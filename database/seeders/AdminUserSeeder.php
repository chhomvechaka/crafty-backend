<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('table_users')->insert([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'firebase_uid' => Str::random(36), // Generate a random string for firebase_uid
            'role_id' => 1, // Assuming 'admin' role has an ID of 1 in the roles table
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Hash a secure password
            'phone_number' => null,
            'address' => null,
        ]);
    }
}
