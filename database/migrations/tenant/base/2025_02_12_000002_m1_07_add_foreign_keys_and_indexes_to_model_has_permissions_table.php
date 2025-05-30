<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $teams = config('permission.teams');

        if (!Schema::hasTable($tableNames['model_has_permissions'])) {
            return;
        }

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($teams) {
            $this->removeIndexes($table, $teams);
            $this->removeForeignKeys($table, $teams);
        });
    }

    /**
     * @param Blueprint $table
     * @param array<string, string> $columnNames
     * 
     * @return void
     */
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

    /**
     * @param Blueprint $table
     * @param array<string, string> $tableNames
     * @param array<string, string> $columnNames
     * @param bool $teams
     * 
     * @return void
     */
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

    /**
     * @param Blueprint $table
     * @param bool $teams
     * 
     * @return void
     */
    private function removeIndexes(Blueprint $table, bool $teams): void
    {
        if (hasIndexExist($table->getTable(), 'model_has_permissions_model_id_model_type_index')) {
            $table->dropIndex('model_has_permissions_model_id_model_type_index');
        }

        if ($teams && hasIndexExist($table->getTable(), 'model_has_permissions_team_foreign_key_index')) {
            $table->dropIndex('model_has_permissions_team_foreign_key_index');
        }
    }

    /**
     * @param Blueprint $table
     * @param bool $teams
     * 
     * @return void
     */
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
