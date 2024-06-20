<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

            DB::table('table_role')->insert([
                ['role_name' => 'buyer'],
                ['role_name' => 'seller'],
                ['role_name' => 'admin'],
            ]);


    }
}
