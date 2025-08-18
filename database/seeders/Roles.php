<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'title' => 'Owner',            
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Admin', 
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Author', 
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Developer', 
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Customer', 
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
