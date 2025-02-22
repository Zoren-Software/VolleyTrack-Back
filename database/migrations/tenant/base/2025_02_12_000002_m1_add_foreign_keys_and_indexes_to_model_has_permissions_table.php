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
        $teams = config('permission.teams');

        if (!Schema::hasTable($tableNames['model_has_permissions'])) {
            return;
        }

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $teams) {
            $this->addIndexes($table, $columnNames);
            $this->addForeignKeys($table, $tableNames, $columnNames, $teams);
        });
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        if (!Schema::hasTable($tableNames['model_has_permissions'])) {
            return;
        }

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($columnNames, $teams) {
            $this->removeIndexes($table, $columnNames, $teams);
            $this->removeForeignKeys($table, $teams);
        });
    }

    private function addIndexes(Blueprint $table, array $columnNames): void
    {
        if (!hasIndexExist($table->getTable(), 'model_has_permissions_model_id_model_type_index')) {
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');
        }

        if (config('permission.teams') && Schema::hasTable('teams') &&
            !hasIndexExist($table->getTable(), 'model_has_permissions_team_foreign_key_index')) {
            $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');
        }
    }

    private function addForeignKeys(Blueprint $table, array $tableNames, array $columnNames, bool $teams): void
    {
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        if (!hasForeignKeyExist($table->getTable(), 'model_has_permissions_permission_id_foreign')) {
            $table->foreign($pivotPermission, 'model_has_permissions_permission_id_foreign')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
        }

        if ($teams && Schema::hasTable('teams') &&
            !hasForeignKeyExist($table->getTable(), 'model_has_permissions_team_foreign_key_foreign')) {
            $table->foreign($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_foreign')
                ->references('id')
                ->on('teams')
                ->onDelete('cascade');
        }
    }

    private function removeIndexes(Blueprint $table, array $columnNames, bool $teams): void
    {
        if (hasIndexExist($table->getTable(), 'model_has_permissions_model_id_model_type_index')) {
            $table->dropIndex('model_has_permissions_model_id_model_type_index');
        }

        if ($teams && hasIndexExist($table->getTable(), 'model_has_permissions_team_foreign_key_index')) {
            $table->dropIndex('model_has_permissions_team_foreign_key_index');
        }
    }

    private function removeForeignKeys(Blueprint $table, bool $teams): void
    {
        if (hasForeignKeyExist($table->getTable(), 'model_has_permissions_permission_id_foreign')) {
            $table->dropForeign('model_has_permissions_permission_id_foreign');
        }

        if ($teams && hasForeignKeyExist($table->getTable(), 'model_has_permissions_team_foreign_key_foreign')) {
            $table->dropForeign('model_has_permissions_team_foreign_key_foreign');
        }
    }
};
