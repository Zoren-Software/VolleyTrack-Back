<?php

namespace Database\Seeders\Tenants;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
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

        /**
         *Já estará como perfil de super administrador, e não precisará relacionar permissões neste perfil
         */
        Role::updateOrCreate(['id' => 1], ['name' => 'Administrador', 'guard_name' => 'sanctum']);

        $technician = Role::updateOrCreate(['id' => 2], ['name' => 'Técnico', 'guard_name' => 'sanctum']);
        Role::updateOrCreate(['id' => 3], ['name' => 'Jogador', 'guard_name' => 'sanctum']);

        /**
         * Permissões Usuário
         */
        $user[] = Permission::updateOrCreate(['id' => 1], ['name' => 'create-user']);
        $user[] = Permission::updateOrCreate(['id' => 2], ['name' => 'edit-user']);
        $user[] = Permission::updateOrCreate(['id' => 3], ['name' => 'list-user']);
        $user[] = Permission::updateOrCreate(['id' => 4], ['name' => 'list-users']);

        $this->sync($technician, $user);

        /**
         * Permissões Time
         */
        $team[] = Permission::updateOrCreate(['id' => 5], ['name' => 'create-team']);
        $team[] = Permission::updateOrCreate(['id' => 6], ['name' => 'edit-team']);
        $team[] = Permission::updateOrCreate(['id' => 7], ['name' => 'list-team']);
        $team[] = Permission::updateOrCreate(['id' => 8], ['name' => 'list-teams']);

        /**
         * Permissões de Configurações
         */
        Permission::updateOrCreate(['id' => 9], ['name' => 'list-role-administrador']);
        $config[] = Permission::updateOrCreate(['id' => 10], ['name' => 'list-role-technician']);
        $config[] = Permission::updateOrCreate(['id' => 11], ['name' => 'list-role-player']);

        /**
         * Permissões de Fundamentos
         */
        $fundamental[] = Permission::updateOrCreate(['id' => 12], ['name' => 'create-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 13], ['name' => 'edit-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 14], ['name' => 'list-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 15], ['name' => 'list-fundamentals']);

        /**
         * Permissões de Fundamentos Especificos
         */
        $fundamental[] = Permission::updateOrCreate(['id' => 16], ['name' => 'create-specific-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 17], ['name' => 'edit-specific-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 18], ['name' => 'list-specific-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 19], ['name' => 'list-specifics-fundamental']);

        /**
         * Permissões de Fundamentos Especificos
         */
        $position[] = Permission::updateOrCreate(['id' => 20], ['name' => 'create-position']);
        $position[] = Permission::updateOrCreate(['id' => 21], ['name' => 'edit-position']);
        $position[] = Permission::updateOrCreate(['id' => 22], ['name' => 'list-position']);
        $position[] = Permission::updateOrCreate(['id' => 23], ['name' => 'list-positions']);

        /**
         * Relacionando Permissões
         */
        $this->sync($technician, $team);
        $this->sync($technician, $config);
        $this->sync($technician, $fundamental);
        $this->sync($technician, $position);

        /**
         * Definir user como perfil de administrador
         */
        User::whereEmail(env('MAIL_FROM_ADDRESS'))->first()->assignRole('Administrador');
        User::whereEmail(env('MAIL_FROM_ADMIN'))->first()->assignRole('Administrador');

        /**
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
