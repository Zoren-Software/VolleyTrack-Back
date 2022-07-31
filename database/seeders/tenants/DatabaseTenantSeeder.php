<?php

namespace Database\Seeders\Tenants;

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
        $this->call([
            UserTableSeeder::class,
            PermissionSeeder::class,
            FundamentalsSeeder::class,
        ]);
    }
}
