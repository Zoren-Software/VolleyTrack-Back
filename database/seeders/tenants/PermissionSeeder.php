<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::updateOrCreate(['id' => 1], ['name' => 'Administrador']);
        Role::updateOrCreate(['id' => 2], ['name' => 'Técnico']);
        Role::updateOrCreate(['id' => 3], ['name' => 'Jogador']);
        
        Permission::updateOrCreate(['id' => 1], ['name' => 'edit articles']);
    }
}
