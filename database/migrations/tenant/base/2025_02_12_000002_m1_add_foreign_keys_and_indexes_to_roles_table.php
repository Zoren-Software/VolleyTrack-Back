<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        // 🚀 Removendo Foreign Keys antes da alteração
        if (Schema::hasTable($tableNames['model_has_roles'])) {
            Schema::table($tableNames['model_has_roles'], function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'model_has_roles_role_id_foreign')) {
                    $table->dropForeign('model_has_roles_role_id_foreign');
                }
            });
        }

        if (Schema::hasTable($tableNames['role_has_permissions'])) {
            Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'role_has_permissions_role_id_foreign')) {
                    $table->dropForeign('role_has_permissions_role_id_foreign');
                }
            });
        }

        // 🚀 Modificando a coluna ID na tabela roles
        if (Schema::hasTable($tableNames['roles'])) {
            Schema::table($tableNames['roles'], function (Blueprint $table) use ($columnNames, $teams, $tableNames) {
                if (!hasAutoIncrement($tableNames['roles'])) {
                    DB::statement("ALTER TABLE {$tableNames['roles']} MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }

                if ($teams && !hasIndexExist($table->getTable(), 'roles_team_foreign_key_index')) {
                    $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
                }

                if ($teams && Schema::hasTable('teams')) {
                    if (!hasForeignKeyExist($table->getTable(), 'roles_team_foreign_key_foreign')) {
                        $table->foreign($columnNames['team_foreign_key'])
                            ->references('id')
                            ->on('teams')
                            ->onDelete('cascade');
                    }
                }

                if ($teams) {
                    $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name'], 'roles_team_name_guard_unique');
                } else {
                    $table->unique(['name', 'guard_name'], 'roles_name_guard_unique');
                }
            });
        }

        // 🚀 Recriando as Foreign Keys
        if (Schema::hasTable($tableNames['model_has_roles'])) {
            Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames) {
                if (!hasForeignKeyExist($table->getTable(), 'model_has_roles_role_id_foreign')) {
                    $table->foreign('role_id')
                        ->references('id')
                        ->on($tableNames['roles'])
                        ->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable($tableNames['role_has_permissions'])) {
            Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
                if (!hasForeignKeyExist($table->getTable(), 'role_has_permissions_role_id_foreign')) {
                    $table->foreign('role_id')
                        ->references('id')
                        ->on($tableNames['roles'])
                        ->onDelete('cascade');
                }
            });
        }
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

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

        if (Schema::hasTable($tableNames['model_has_roles'])) {
            Schema::table($tableNames['model_has_roles'], function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'model_has_roles_role_id_foreign')) {
                    $table->dropForeign('model_has_roles_role_id_foreign');
                }
            });
        }

        if (Schema::hasTable($tableNames['role_has_permissions'])) {
            Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'role_has_permissions_role_id_foreign')) {
                    $table->dropForeign('role_has_permissions_role_id_foreign');
                }
            });
        }
    }
};
