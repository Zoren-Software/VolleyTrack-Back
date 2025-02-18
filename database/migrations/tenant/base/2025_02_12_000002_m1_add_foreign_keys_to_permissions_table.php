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

        // 🚀 Removendo Foreign Keys antes da alteração
        if (Schema::hasTable($tableNames['model_has_permissions'])) {
            Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'model_has_permissions_permission_id_foreign')) {
                    $table->dropForeign('model_has_permissions_permission_id_foreign');
                }
            });
        }

        if (Schema::hasTable($tableNames['role_has_permissions'])) {
            Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'role_has_permissions_permission_id_foreign')) {
                    $table->dropForeign('role_has_permissions_permission_id_foreign');
                }
            });
        }

        // 🚀 Modificando a coluna ID na tabela permissions
        if (Schema::hasTable($tableNames['permissions'])) {
            Schema::table($tableNames['permissions'], function (Blueprint $table) use ($tableNames) {
                if (!hasAutoIncrement($tableNames['permissions'])) {
                    DB::statement("ALTER TABLE {$tableNames['permissions']} MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }

                // Criando índice único, se necessário
                if (!hasIndexExist($tableNames['permissions'], 'permissions_name_guard_name_unique')) {
                    $table->unique(['name', 'guard_name'], 'permissions_name_guard_name_unique');
                }
            });
        }

        // 🚀 Recriando as Foreign Keys depois da alteração
        if (Schema::hasTable($tableNames['model_has_permissions'])) {
            Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {
                if (!hasForeignKeyExist($table->getTable(), 'model_has_permissions_permission_id_foreign')) {
                    $table->foreign('permission_id', 'model_has_permissions_permission_id_foreign')
                        ->references('id')
                        ->on($tableNames['permissions'])
                        ->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable($tableNames['role_has_permissions'])) {
            Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
                if (!hasForeignKeyExist($table->getTable(), 'role_has_permissions_permission_id_foreign')) {
                    $table->foreign('permission_id', 'role_has_permissions_permission_id_foreign')
                        ->references('id')
                        ->on($tableNames['permissions'])
                        ->onDelete('cascade');
                }
            });
        }
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');

        // 🚀 Removendo as Foreign Keys antes de desfazer a alteração
        if (Schema::hasTable($tableNames['model_has_permissions'])) {
            Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'model_has_permissions_permission_id_foreign')) {
                    $table->dropForeign('model_has_permissions_permission_id_foreign');
                }
            });
        }

        if (Schema::hasTable($tableNames['role_has_permissions'])) {
            Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'role_has_permissions_permission_id_foreign')) {
                    $table->dropForeign('role_has_permissions_permission_id_foreign');
                }
            });
        }

        if (Schema::hasTable($tableNames['permissions'])) {
            Schema::table($tableNames['permissions'], function (Blueprint $table) use ($tableNames) {
                if (hasIndexExist($tableNames['permissions'], 'permissions_name_guard_name_unique')) {
                    $table->dropUnique('permissions_name_guard_name_unique');
                }
            });
        }

        // 🚀 Recriando as Foreign Keys depois da reversão
        if (Schema::hasTable($tableNames['model_has_permissions'])) {
            Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {
                if (!hasForeignKeyExist($table->getTable(), 'model_has_permissions_permission_id_foreign')) {
                    $table->foreign('permission_id', 'model_has_permissions_permission_id_foreign')
                        ->references('id')
                        ->on($tableNames['permissions'])
                        ->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable($tableNames['role_has_permissions'])) {
            Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
                if (!hasForeignKeyExist($table->getTable(), 'role_has_permissions_permission_id_foreign')) {
                    $table->foreign('permission_id', 'role_has_permissions_permission_id_foreign')
                        ->references('id')
                        ->on($tableNames['permissions'])
                        ->onDelete('cascade');
                }
            });
        }
    }
};
