<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            TypeUserSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
