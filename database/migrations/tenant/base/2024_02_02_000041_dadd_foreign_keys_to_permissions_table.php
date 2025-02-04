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

        if (Schema::hasTable($tableNames['permissions'])) {
            Schema::table($tableNames['permissions'], function (Blueprint $table) use ($tableNames) {
                if (!hasAutoIncrement($tableNames['permissions'])) {
                    DB::statement("ALTER TABLE {$tableNames['permissions']} MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }

                // Verificação e Criação do Índice
                if (!hasIndexExist($tableNames['permissions'], 'permissions_name_guard_name_unique')) {
                    $table->unique(['name', 'guard_name'], 'permissions_name_guard_name_unique');
                }

                // Exemplo de Foreign Key (caso necessário)
                if (Schema::hasTable($tableNames['roles'])) {
                    if (!hasForeignKeyExist($tableNames['permissions'], 'permissions_role_id_foreign')) {
                        $table->foreignId('role_id')
                            ->nullable()
                            ->constrained($tableNames['roles'])
                            ->onDelete('cascade');
                    }
                }
            });
        }
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');

        if (Schema::hasTable($tableNames['permissions'])) {
            Schema::table($tableNames['permissions'], function (Blueprint $table) {
                // Remover o Índice se existir
                if (hasIndexExist($table->getTable(), 'permissions_name_guard_name_unique')) {
                    $table->dropUnique('permissions_name_guard_name_unique');
                }

                // Remover Foreign Key se existir
                if (hasForeignKeyExist($table->getTable(), 'permissions_role_id_foreign')) {
                    $table->dropForeign('permissions_role_id_foreign');
                }
            });
        }
    }
};
