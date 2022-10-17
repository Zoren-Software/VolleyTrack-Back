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
        $admin = Role::updateOrCreate(['id' => 1], ['name' => 'Administrador', 'guard_name' => 'sanctum']);
        $technician = Role::updateOrCreate(['id' => 2], ['name' => 'Técnico', 'guard_name' => 'sanctum']);
        $player = Role::updateOrCreate(['id' => 3], ['name' => 'Jogador', 'guard_name' => 'sanctum']);

        /**
         * Permissões Usuário
         */
        $user[] = Permission::updateOrCreate(['id' => 1], ['name' => 'create-user']);
        $user[] = Permission::updateOrCreate(['id' => 2], ['name' => 'edit-user']);
        $user[] = Permission::updateOrCreate(['id' => 3], ['name' => 'list-user']);
        $user[] = Permission::updateOrCreate(['id' => 4], ['name' => 'list-users']);
        $user[] = Permission::updateOrCreate(['id' => 5], ['name' => 'delete-user']);

        /**
         * Permissões Time
         */
        $team[] = Permission::updateOrCreate(['id' => 6], ['name' => 'create-team']);
        $team[] = Permission::updateOrCreate(['id' => 7], ['name' => 'edit-team']);
        $team[] = Permission::updateOrCreate(['id' => 8], ['name' => 'list-team']);
        $team[] = Permission::updateOrCreate(['id' => 9], ['name' => 'list-teams']);
        $team[] = Permission::updateOrCreate(['id' => 10], ['name' => 'delete-team']);

        /**
         * Permissões de Fundamentos
         */
        $fundamental[] = Permission::updateOrCreate(['id' => 11], ['name' => 'create-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 12], ['name' => 'edit-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 13], ['name' => 'list-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 14], ['name' => 'list-fundamentals']);
        $fundamental[] = Permission::updateOrCreate(['id' => 15], ['name' => 'delete-fundamental']);

        /**
         * Permissões de Fundamentos Específicos
         */
        $fundamental[] = Permission::updateOrCreate(['id' => 16], ['name' => 'create-specific-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 17], ['name' => 'edit-specific-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 18], ['name' => 'list-specific-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 19], ['name' => 'list-specifics-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 20], ['name' => 'delete-specific-fundamental']);

        /**
         * Permissões de Posições
         */
        $position[] = Permission::updateOrCreate(['id' => 21], ['name' => 'create-position']);
        $position[] = Permission::updateOrCreate(['id' => 22], ['name' => 'edit-position']);
        $position[] = Permission::updateOrCreate(['id' => 23], ['name' => 'list-position']);
        $position[] = Permission::updateOrCreate(['id' => 24], ['name' => 'list-positions']);
        $position[] = Permission::updateOrCreate(['id' => 25], ['name' => 'delete-position']);

        /**
         * Permissões de Funções
         */
        Permission::updateOrCreate(['id' => 26], ['name' => 'list-role-administrador']);
        $role[] = Permission::updateOrCreate(['id' => 27], ['name' => 'list-role-technician']);
        $role[] = Permission::updateOrCreate(['id' => 28], ['name' => 'list-role-player']);

        /**
         * Permissões de Treinos
         */
        $training[] = Permission::updateOrCreate(['id' => 29], ['name' => 'create-training']);
        $training[] = Permission::updateOrCreate(['id' => 30], ['name' => 'edit-training']);
        $training[] = Permission::updateOrCreate(['id' => 31], ['name' => 'list-training']);
        $training[] = Permission::updateOrCreate(['id' => 32], ['name' => 'list-trainings']);
        $training[] = Permission::updateOrCreate(['id' => 33], ['name' => 'delete-training']);

        /**
         * Permissões de Configurações
         */
        $config[] = Permission::updateOrCreate(['id' => 34], ['name' => 'edit-config']);
        $config[] = Permission::updateOrCreate(['id' => 35], ['name' => 'list-config']);

        /**
         * Permissões de Configurações de Treino
         */
        $trainingConfig[] = Permission::updateOrCreate(['id' => 34], ['name' => 'edit-training-config']);
        $trainingConfig[] = Permission::updateOrCreate(['id' => 35], ['name' => 'list-training-config']);

        /**
         * Relacionando Permissões
         */
        $this->sync($technician, $user);
        $this->sync($technician, $team);
        $this->sync($technician, $role);
        $this->sync($technician, $fundamental);
        $this->sync($technician, $position);
        $this->sync($technician, $training);
        $this->sync($technician, $config);

        $this->sync($admin, $user);
        $this->sync($admin, $team);
        $this->sync($admin, $role);
        $this->sync($admin, $fundamental);
        $this->sync($admin, $position);
        $this->sync($admin, $training);
        $this->sync($admin, $config);

        $this->sync($player, $user);
        $this->sync($player, $team);
        $this->sync($player, $role);
        $this->sync($player, $fundamental);
        $this->sync($player, $position);
        $this->sync($player, $training);
        $this->sync($player, $config);

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
