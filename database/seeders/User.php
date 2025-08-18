<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Seeder
{
    public function run()
    {
        // Get the ID of the "Owner" role
        $ownerRoleId = DB::table('roles')->where('title', 'Owner')->value('id');

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'support@g-techsolutions.com.au',
            'phone' => '03468887736',
            'password' => Hash::make('gtech@1122!'),
            'role_id' => $ownerRoleId,
            'created_by' => null,
            'profile_image' => '/user.jpg',
            'active' => 1,
            'remember_token' => '',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Dev Team',
            'email' => 'dev@gmail.com',
            'phone' => '03468887736',
            'password' => Hash::make('12345678'),
            'role_id' => $ownerRoleId,
            'created_by' => null,
            'profile_image' => '/user.jpg',
            'active' => 1,
            'remember_token' => '',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
