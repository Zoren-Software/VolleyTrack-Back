<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $teams = config('permission.teams');

        if (Schema::hasTable($tableNames['model_has_roles'])) {
            Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $pivotRole, $columnNames, $teams) {
                if (!hasAutoIncrement($tableNames['model_has_roles'])) {
                    DB::statement("ALTER TABLE {$tableNames['model_has_roles']} MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }

                // Índice para o campo model_type + model_morph_key
                if (!hasIndexExist($table->getTable(), 'model_has_roles_model_id_model_type_index')) {
                    $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');
                }

                // Chave estrangeira para role_id
                if (!hasForeignKeyExist($table->getTable(), 'model_has_roles_role_id_foreign')) {
                    $table->foreign($pivotRole, 'model_has_roles_role_id_foreign')
                        ->references('id')
                        ->on($tableNames['roles'])
                        ->onDelete('cascade');
                }

                // Chave estrangeira e índice para o time, se aplicável
                if ($teams && Schema::hasTable('teams')) {
                    if (!hasIndexExist($table->getTable(), 'model_has_roles_team_foreign_key_index')) {
                        $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');
                    }

                    if (!hasForeignKeyExist($table->getTable(), 'model_has_roles_team_foreign_key_foreign')) {
                        $table->foreign($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_foreign')
                            ->references('id')
                            ->on('teams')
                            ->onDelete('cascade');
                    }
                }
            });
        }
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        if (Schema::hasTable($tableNames['model_has_roles'])) {
            Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($columnNames, $teams) {

                // Remover índices
                if (hasIndexExist($table->getTable(), 'model_has_roles_model_id_model_type_index')) {
                    $table->dropIndex('model_has_roles_model_id_model_type_index');
                }

                if ($teams && hasIndexExist($table->getTable(), 'model_has_roles_team_foreign_key_index')) {
                    $table->dropIndex('model_has_roles_team_foreign_key_index');
                }

                // Remover chaves estrangeiras
                if (hasForeignKeyExist($table->getTable(), 'model_has_roles_role_id_foreign')) {
                    $table->dropForeign('model_has_roles_role_id_foreign');
                }

                if ($teams && hasForeignKeyExist($table->getTable(), 'model_has_roles_team_foreign_key_foreign')) {
                    $table->dropForeign('model_has_roles_team_foreign_key_foreign');
                }
            });
        }
    }
};
