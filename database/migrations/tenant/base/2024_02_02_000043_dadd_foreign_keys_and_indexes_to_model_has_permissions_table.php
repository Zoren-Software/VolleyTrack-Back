<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $teams = config('permission.teams');

        if (Schema::hasTable($tableNames['model_has_permissions'])) {
            Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $pivotPermission, $columnNames, $teams) {

                // Índice para o campo model_type + model_morph_key
                if (!hasIndexExist($table->getTable(), 'model_has_permissions_model_id_model_type_index')) {
                    $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');
                }

                // Chave estrangeira para permission_id
                if (!hasForeignKeyExist($table->getTable(), 'model_has_permissions_permission_id_foreign')) {
                    $table->foreign($pivotPermission, 'model_has_permissions_permission_id_foreign')
                        ->references('id')
                        ->on($tableNames['permissions'])
                        ->onDelete('cascade');
                }

                // Chave estrangeira e índice para o time, se aplicável
                if ($teams && Schema::hasTable('teams')) {
                    if (!hasIndexExist($table->getTable(), 'model_has_permissions_team_foreign_key_index')) {
                        $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');
                    }

                    if (!hasForeignKeyExist($table->getTable(), 'model_has_permissions_team_foreign_key_foreign')) {
                        $table->foreign($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_foreign')
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

        if (Schema::hasTable($tableNames['model_has_permissions'])) {
            Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($columnNames, $teams) {

                // Remover índices
                if (hasIndexExist($table->getTable(), 'model_has_permissions_model_id_model_type_index')) {
                    $table->dropIndex('model_has_permissions_model_id_model_type_index');
                }

                if ($teams && hasIndexExist($table->getTable(), 'model_has_permissions_team_foreign_key_index')) {
                    $table->dropIndex('model_has_permissions_team_foreign_key_index');
                }

                // Remover chaves estrangeiras
                if (hasForeignKeyExist($table->getTable(), 'model_has_permissions_permission_id_foreign')) {
                    $table->dropForeign('model_has_permissions_permission_id_foreign');
                }

                if ($teams && hasForeignKeyExist($table->getTable(), 'model_has_permissions_team_foreign_key_foreign')) {
                    $table->dropForeign('model_has_permissions_team_foreign_key_foreign');
                }
            });
        }
    }
};
