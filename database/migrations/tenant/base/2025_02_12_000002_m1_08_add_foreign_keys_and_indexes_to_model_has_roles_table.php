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
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $teams = config('permission.teams');

        if (!Schema::hasTable($tableNames['model_has_roles'])) {
            return;
        }

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole, $teams) {
            $this->addIndexes($table, $columnNames);
            $this->addForeignKeys($table, $tableNames, $columnNames, $pivotRole, $teams);
        });
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $teams = config('permission.teams');

        if (!Schema::hasTable($tableNames['model_has_roles'])) {
            return;
        }

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($teams) {
            $this->removeIndexes($table, $teams);
            $this->removeForeignKeys($table, $teams);
        });
    }

    private function addIndexes(Blueprint $table, array $columnNames): void
    {
        if (!hasIndexExist($table->getTable(), 'model_has_roles_model_id_model_type_index')) {
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');
        }

        if (config('permission.teams') && Schema::hasTable('teams') &&
            !hasIndexExist($table->getTable(), 'model_has_roles_team_foreign_key_index')) {
            $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');
        }
    }

    private function addForeignKeys(Blueprint $table, array $tableNames, array $columnNames, string $pivotRole, bool $teams): void
    {
        if (!hasForeignKeyExist($table->getTable(), 'model_has_roles_role_id_foreign')) {
            $table->foreign($pivotRole, 'model_has_roles_role_id_foreign')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');
        }

        if ($teams && Schema::hasTable('teams') &&
            !hasForeignKeyExist($table->getTable(), 'model_has_roles_team_foreign_key_foreign')) {
            $table->foreign($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_foreign')
                ->references('id')
                ->on('teams')
                ->onDelete('cascade');
        }
    }

    private function removeIndexes(Blueprint $table, bool $teams): void
    {
        if (hasIndexExist($table->getTable(), 'model_has_roles_model_id_model_type_index')) {
            $table->dropIndex('model_has_roles_model_id_model_type_index');
        }

        if ($teams && hasIndexExist($table->getTable(), 'model_has_roles_team_foreign_key_index')) {
            $table->dropIndex('model_has_roles_team_foreign_key_index');
        }
    }

    private function removeForeignKeys(Blueprint $table, bool $teams): void
    {
        if (hasForeignKeyExist($table->getTable(), 'model_has_roles_role_id_foreign')) {
            $table->dropForeign('model_has_roles_role_id_foreign');
        }

        if ($teams && hasForeignKeyExist($table->getTable(), 'model_has_roles_team_foreign_key_foreign')) {
            $table->dropForeign('model_has_roles_team_foreign_key_foreign');
        }
    }
};
