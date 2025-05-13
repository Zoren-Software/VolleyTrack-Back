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
         *Já estará como perfil de super admin, e não precisará relacionar permissões neste perfil
         */
        $admin = Role::updateOrCreate(['id' => 1], ['name' => 'admin', 'guard_name' => 'sanctum']);
        $technician = Role::updateOrCreate(['id' => 2], ['name' => 'technician', 'guard_name' => 'sanctum']);
        $player = Role::updateOrCreate(['id' => 3], ['name' => 'player', 'guard_name' => 'sanctum']);

        /**
         * Permissões Usuário
         */
        $user[] = Permission::updateOrCreate(['id' => 1], ['name' => 'edit-user']);
        $user[] = Permission::updateOrCreate(['id' => 2], ['name' => 'view-user']);

        /**
         * Permissões Time
         */
        $team[] = Permission::updateOrCreate(['id' => 3], ['name' => 'edit-team']);
        $team[] = Permission::updateOrCreate(['id' => 4], ['name' => 'view-team']);

        /**
         * Permissões de Fundamentos
         */
        $fundamental[] = Permission::updateOrCreate(['id' => 5], ['name' => 'edit-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 6], ['name' => 'view-fundamental']);

        /**
         * Permissões de Fundamentos Específicos
         */
        $fundamental[] = Permission::updateOrCreate(['id' => 7], ['name' => 'edit-specific-fundamental']);
        $fundamental[] = Permission::updateOrCreate(['id' => 8], ['name' => 'view-specific-fundamental']);

        /**
         * Permissões de Posições
         */
        $position[] = Permission::updateOrCreate(['id' => 9], ['name' => 'edit-position']);
        $position[] = Permission::updateOrCreate(['id' => 10], ['name' => 'view-position']);

        /**
         * Permissões de Funções
         */
        $role[] = Permission::updateOrCreate(['id' => 11], ['name' => 'edit-role']);
        $role[] = Permission::updateOrCreate(['id' => 12], ['name' => 'view-role']);

        /**
         * Permissões de Funções Específicas
         */
        $roleAdmin[] = Permission::updateOrCreate(['id' => 13], ['name' => 'view-role-admin']);
        $roleTechnician[] = Permission::updateOrCreate(['id' => 14], ['name' => 'view-role-technician']);
        $rolePlayer[] = Permission::updateOrCreate(['id' => 15], ['name' => 'view-role-player']);

        /**
         * Permissões de Treinos
         */
        $training[] = Permission::updateOrCreate(['id' => 16], ['name' => 'edit-training']);
        $training[] = Permission::updateOrCreate(['id' => 17], ['name' => 'view-training']);

        /**
         * Permissões de Configurações
         */
        $config[] = Permission::updateOrCreate(['id' => 18], ['name' => 'edit-config']);
        $config[] = Permission::updateOrCreate(['id' => 19], ['name' => 'view-config']);

        /**
         * Permissões de Configurações de Treino
         */
        $trainingConfig[] = Permission::updateOrCreate(['id' => 20], ['name' => 'edit-training-config']);
        $trainingConfig[] = Permission::updateOrCreate(['id' => 21], ['name' => 'view-training-config']);

        /**
         * Permissões de Confirmação de Treino
         */
        $confirmationTraining[] = Permission::updateOrCreate(['id' => 22], ['name' => 'view-confirmation-training']);

        /**
         * Permissões Time Levels
         */
        $teamLevels[] = Permission::updateOrCreate(['id' => 23], ['name' => 'view-team-levels']);

        /**
         * Relacionando Permissões
         */
        $this->sync($admin, $user);
        $this->sync($admin, $team);
        $this->sync($admin, $teamLevels);
        $this->sync($admin, $role);
        $this->sync($admin, $fundamental);
        $this->sync($admin, $position);
        $this->sync($admin, $training);
        $this->sync($admin, $config);
        $this->sync($admin, $trainingConfig);
        $this->sync($admin, $confirmationTraining);
        $this->sync($admin, $roleAdmin);

        $this->sync($technician, $user);
        $this->sync($technician, $team);
        $this->sync($technician, $teamLevels);
        $this->sync($technician, $role);
        $this->sync($technician, $fundamental);
        $this->sync($technician, $position);
        $this->sync($technician, $training);
        $this->sync($technician, $config);
        $this->sync($technician, $trainingConfig);
        $this->sync($technician, $confirmationTraining);
        $this->sync($technician, $roleTechnician);

        $this->sync($player, $user);
        $this->sync($player, $team);
        $this->sync($player, $teamLevels);
        $this->sync($player, $role);
        $this->sync($player, $fundamental);
        $this->sync($player, $position);
        $this->sync($player, $training);
        $this->sync($player, $config);
        $this->sync($player, $trainingConfig);
        $this->sync($player, $confirmationTraining);
        $this->sync($player, $rolePlayer);

        /**
         * Definir user como perfil de admin
         */
        User::whereEmail(env('MAIL_FROM_ADDRESS'))->first()->assignRole('admin');
        User::whereEmail(env('MAIL_FROM_ADMIN'))->first()->assignRole('admin');

        /**
         * Definir user como perfil de técnico
         */
        if (env('APP_DEBUG') && env('APP_ENV') === 'local') {
            User::whereEmail(env('MAIL_FROM_TEST_TECHNICIAN'))->first()->assignRole('technician');
            User::whereEmail(env('MAIL_FROM_TEST_PLAYER'))->first()->assignRole('player');
        }
    }

    public function sync($role, $permissions)
    {
        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }
    }
}
