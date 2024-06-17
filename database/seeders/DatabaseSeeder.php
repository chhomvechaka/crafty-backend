<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Call other seed classes here
        $this->call([
            RolesTableSeeder::class,
            AdminUserSeeder::class,
            // Add other seeders here

        ]);
    }
}
