<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // buyer
        DB::table('table_users')->insert([
            [
                'firstname' => 'By',
                'lastname' => 'Yer1',
                'firebase_uid' => Str::random(36),
                'role_id' => 1,
                'email' => 'buyer1@gmail.com',
                'password' => Hash::make('123456'),
                'phone_number' => '0123456', // Make sure phone numbers are stored as strings
                'address' => 'Fun Mall, Street 315, Sangkat Boeung Kak Ti Pir, Khan Toul Kork, Phnom Penh, 120408, Cambodia',
            ],
            [
                'firstname' => 'By',
                'lastname' => 'Yer2',
                'firebase_uid' => Str::random(36),
                'role_id' => 1,
                'email' => 'buyer2@gmail.com',
                'password' => Hash::make('123456'),
                'phone_number' => '0123456',
                'address' => 'Fun Mall, Street 315, Sangkat Boeung Kak Ti Pir',
            ],
            [
                'firstname' => 'By',
                'lastname' => 'Yer3',
                'firebase_uid' => Str::random(36),
                'role_id' => 1,
                'email' => 'buyer3@gmail.com',
                'password' => Hash::make('123456'),
                'phone_number' => '0123456',
                'address' => 'Street 315, Sangkat Boeung Kak Ti Pir',
            ],
            [
                'firstname' => 'Sel',
                'lastname' => 'Ler1',
                'firebase_uid' => Str::random(36),
                'role_id' => 2,
                'email' => 'seller1@gmail.com',
                'password' => Hash::make('123456'),
                'phone_number' => '0123456',
                'address' => 'Fun Mall, Sangkat Boeung Kak Ti Pir, Khan Toul Kork, Phnom Penh, 120408, Cambodia',
            ],
            [
                'firstname' => 'Sel',
                'lastname' => 'Ler2',
                'firebase_uid' => Str::random(36),
                'role_id' => 2,
                'email' => 'seller2@gmail.com',
                'password' => Hash::make('123456'),
                'phone_number' => '0123456',
                'address' => 'Street 315, Sangkat Boeung Kak Ti Pir',
            ],
            [
                'firstname' => 'Sel',
                'lastname' => 'Ler3',
                'firebase_uid' => Str::random(36),
                'role_id' => 2,
                'email' => 'seller3@gmail.com',
                'password' => Hash::make('123456'),
                'phone_number' => '0123456',
                'address' => 'Sangkat Boeung Kak Ti Pir Bruh',
            ],
            [
                'firstname' => 'Sel',
                'lastname' => 'Ler4',
                'firebase_uid' => Str::random(36),
                'role_id' => 2,
                'email' => 'seller4@gmail.com',
                'password' => Hash::make('123456'),
                'phone_number' => '0123456',
                'address' => 'No logo Ti Pir Bruh',
            ]
        ]);
    }
}
