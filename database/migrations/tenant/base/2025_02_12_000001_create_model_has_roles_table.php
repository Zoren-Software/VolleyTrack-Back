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
        $teams = config('permission.teams');

        if (!is_array($tableNames) || !is_array($columnNames)) {
            throw new \RuntimeException('Permission table_names or column_names not properly configured.');
        }

        /** @var array<string, string> $tableNames */
        $tableNames = $tableNames;

        /** @var array<string, string> $columnNames */
        $columnNames = $columnNames;

        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';

        if (!Schema::hasTable($tableNames['model_has_roles'])) {
            Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($pivotRole, $columnNames, $teams) {
                $table->unsignedBigInteger($pivotRole);
                $table->string('model_type');
                $table->unsignedBigInteger($columnNames['model_morph_key']);
                $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

                if ($teams) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                    $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');

                    $table->primary([
                        $columnNames['team_foreign_key'],
                        $pivotRole,
                        $columnNames['model_morph_key'],
                        'model_type',
                    ], 'model_has_roles_primary');
                } else {
                    $table->primary([
                        $pivotRole,
                        $columnNames['model_morph_key'],
                        'model_type',
                    ], 'model_has_roles_primary');
                }
            });
        }
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');

        if (!is_array($tableNames)) {
            throw new \RuntimeException('Permission table_names not properly configured.');
        }

        /** @var array<string, string> $tableNames */
        $tableNames = $tableNames;

        Schema::dropIfExists($tableNames['model_has_roles']);
    }
};
