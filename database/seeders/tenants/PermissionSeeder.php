<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /*
         *Já estará como perfil de super administrador, e não precisará relacionar permissões neste perfil
         */
        Role::updateOrCreate(['id' => 1], ['name' => 'Administrador', 'guard_name' => 'sanctum']);

        $technician = Role::updateOrCreate(['id' => 2], ['name' => 'Técnico', 'guard_name' => 'sanctum']);
        Role::updateOrCreate(['id' => 3], ['name' => 'Jogador', 'guard_name' => 'sanctum']);

        /*
         * Permissões Usuário
         */
        $user[] = Permission::updateOrCreate(['id' => 1], ['name' => 'create-user']);
        $user[] = Permission::updateOrCreate(['id' => 2], ['name' => 'edit-user']);
        $user[] = Permission::updateOrCreate(['id' => 3], ['name' => 'list-user']);
        $user[] = Permission::updateOrCreate(['id' => 4], ['name' => 'list-users']);

        //$this->sync($technician, $user);

        /*
         * Permissões Time
         */
        $team[] = Permission::updateOrCreate(['id' => 5], ['name' => 'create-team']);
        $team[] = Permission::updateOrCreate(['id' => 6], ['name' => 'edit-team']);
        $team[] = Permission::updateOrCreate(['id' => 7], ['name' => 'list-team']);
        $team[] = Permission::updateOrCreate(['id' => 8], ['name' => 'list-teams']);

        /*
         * Permissões de Configurações
         */
        Permission::updateOrCreate(['id' => 9], ['name' => 'list-role-administrador']);
        $config[] = Permission::updateOrCreate(['id' => 10], ['name' => 'list-role-technician']);
        $config[] = Permission::updateOrCreate(['id' => 11], ['name' => 'list-role-player']);

        $this->sync($technician, $team);
        $this->sync($technician, $config);

        /*
         * Definir user como perfil de administrador
         */
        User::whereEmail(env('MAIL_FROM_ADDRESS'))->first()->assignRole('Administrador');
        User::whereEmail(env('MAIL_FROM_ADMIN'))->first()->assignRole('Administrador');

        /*
         * Definir user como perfil de técnico
         */
        if (env('APP_DEBUG')) {
            User::whereEmail(env('MAIL_FROM_TEST_TECHNICIAN'))->first()->assignRole('Técnico');
            User::whereEmail(env('MAIL_FROM_TEST_PLAYER'))->first()->assignRole('Jogador');
        }
    }

    public function sync($role, $permissions)
    {
        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }
    }
}
