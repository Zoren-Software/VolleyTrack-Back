<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $teams = config('permission.teams');

        $this->removeForeignKeys($tableNames);
        $this->modifyRolesTable($tableNames, $teams);
        $this->recreateForeignKeys($tableNames);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $this->removeIndexesAndForeignKeys($tableNames);
    }

    /**
     * @param array<string, string> $tableNames
     * 
     * @return void
     */
    private function removeForeignKeys(array $tableNames): void
    {
        $foreignKeys = [
            'model_has_roles' => 'model_has_roles_role_id_foreign',
            'role_has_permissions' => 'role_has_permissions_role_id_foreign',
        ];

        foreach ($foreignKeys as $table => $foreignKey) {
            if (Schema::hasTable($tableNames[$table])) {
                Schema::table($tableNames[$table], function (Blueprint $table) use ($foreignKey) {
                    if (hasForeignKeyExist($table->getTable(), $foreignKey)) {
                        $table->dropForeign($foreignKey);
                    }
                });
            }
        }
    }

    /**
     * @param array<string, string> $tableNames
     * @param bool $teams
     * 
     * @return void
     */
    private function modifyRolesTable(array $tableNames, bool $teams): void
    {
        if (!Schema::hasTable($tableNames['roles'])) {
            return;
        }

        Schema::table($tableNames['roles'], function (Blueprint $table) use ($tableNames, $teams) {
            if (!hasAutoIncrement($tableNames['roles'])) {
                DB::statement("ALTER TABLE {$tableNames['roles']} MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
            }

            if ($teams) {
                $this->addTeamForeignKey($table);
                $table->unique(['team_id', 'name', 'guard_name'], 'roles_team_name_guard_unique');
            } else {
                $table->unique(['name', 'guard_name'], 'roles_name_guard_unique');
            }
        });
    }

    /**
     * @param Blueprint $table
     * 
     * @return void
     */
    private function addTeamForeignKey(Blueprint $table): void
    {
        if (!Schema::hasTable('teams')) {
            return;
        }

        if (!hasIndexExist($table->getTable(), 'roles_team_foreign_key_index')) {
            $table->index('team_id', 'roles_team_foreign_key_index');
        }

        if (!hasForeignKeyExist($table->getTable(), 'roles_team_foreign_key_foreign')) {
            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->onDelete('cascade');
        }
    }

    /**
     * @param array<string, string> $tableNames
     * 
     * @return void
     */
    private function recreateForeignKeys(array $tableNames): void
    {
        $foreignKeys = [
            'model_has_roles' => 'model_has_roles_role_id_foreign',
            'role_has_permissions' => 'role_has_permissions_role_id_foreign',
        ];

        foreach ($foreignKeys as $table => $foreignKey) {
            if (Schema::hasTable($tableNames[$table])) {
                Schema::table($tableNames[$table], function (Blueprint $table) use ($tableNames, $foreignKey) {
                    if (!hasForeignKeyExist($table->getTable(), $foreignKey)) {
                        $table->foreign('role_id')
                            ->references('id')
                            ->on($tableNames['roles'])
                            ->onDelete('cascade');
                    }
                });
            }
        }
    }

    /**
     * @param array<string, string> $tableNames
     * 
     * @return void
     */
    private function removeIndexesAndForeignKeys(array $tableNames): void
    {
        if (Schema::hasTable($tableNames['roles'])) {
            Schema::table($tableNames['roles'], function (Blueprint $table) {
                if (hasIndexExist($table->getTable(), 'roles_team_foreign_key_index')) {
                    $table->dropIndex('roles_team_foreign_key_index');
                }

                if (hasForeignKeyExist($table->getTable(), 'roles_team_foreign_key_foreign')) {
                    $table->dropForeign('roles_team_foreign_key_foreign');
                }
            });
        }

        $this->removeForeignKeys($tableNames);
    }
};
