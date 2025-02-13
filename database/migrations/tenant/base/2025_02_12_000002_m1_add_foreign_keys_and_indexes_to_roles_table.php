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

        if (Schema::hasTable($tableNames['roles'])) {
            Schema::table($tableNames['roles'], function (Blueprint $table) use ($columnNames, $teams, $tableNames) {
                if (!hasAutoIncrement($tableNames['roles'])) {
                    DB::statement("ALTER TABLE {$tableNames['roles']} MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }
                
                // Verificação e Criação do Índice
                if ($teams && !hasIndexExist($table->getTable(), 'roles_team_foreign_key_index')) {
                    $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
                }

                // Verificação e Criação da Chave Estrangeira, se aplicável
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
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (Schema::hasTable($tableNames['roles'])) {
            Schema::table($tableNames['roles'], function (Blueprint $table) use ($columnNames) {
                // Remover o Índice se existir
                if (hasIndexExist($table->getTable(), 'roles_team_foreign_key_index')) {
                    $table->dropIndex('roles_team_foreign_key_index');
                }

                // Remover a Chave Estrangeira se existir
                if (hasForeignKeyExist($table->getTable(), 'roles_team_foreign_key_foreign')) {
                    $table->dropForeign('roles_team_foreign_key_foreign');
                }
            });
        }
    }
};
