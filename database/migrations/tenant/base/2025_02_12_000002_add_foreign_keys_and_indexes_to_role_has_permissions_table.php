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
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';

        if (Schema::hasTable($tableNames['role_has_permissions'])) {
            Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames, $pivotPermission, $pivotRole) {
                // Verificação e adição da foreign key para permission_id
                if (!hasForeignKeyExist($table->getTable(), 'role_has_permissions_permission_id_foreign')) {
                    $table->foreign($pivotPermission, 'role_has_permissions_permission_id_foreign')
                        ->references('id')
                        ->on($tableNames['permissions'])
                        ->onDelete('cascade');
                }

                // Verificação e adição da foreign key para role_id
                if (!hasForeignKeyExist($table->getTable(), 'role_has_permissions_role_id_foreign')) {
                    $table->foreign($pivotRole, 'role_has_permissions_role_id_foreign')
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
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';

        if (Schema::hasTable($tableNames['role_has_permissions'])) {
            Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) use ($pivotPermission, $pivotRole) {

                // Remover chaves estrangeiras
                if (hasForeignKeyExist($table->getTable(), 'role_has_permissions_permission_id_foreign')) {
                    $table->dropForeign('role_has_permissions_permission_id_foreign');
                }

                if (hasForeignKeyExist($table->getTable(), 'role_has_permissions_role_id_foreign')) {
                    $table->dropForeign('role_has_permissions_role_id_foreign');
                }
            });
        }
    }
};
