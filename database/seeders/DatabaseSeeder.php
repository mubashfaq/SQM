<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Roles;
use Database\Seeders\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(Roles::class);
        $this->call(User::class);
    }
}
