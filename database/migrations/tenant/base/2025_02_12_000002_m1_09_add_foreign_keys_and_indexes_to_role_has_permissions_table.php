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

        if (!Schema::hasTable($tableNames['role_has_permissions'])) {
            return;
        }

        Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $this->addForeignKeys($table, $tableNames, $columnNames);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        if (!Schema::hasTable($tableNames['role_has_permissions'])) {
            return;
        }

        Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) {
            $this->removeForeignKeys($table);
        });
    }

    /**
     * @param Blueprint $table
     * @param array<string, string> $tableNames
     * @param array<string, string> $columnNames
     * 
     * @return void
     */
    private function addForeignKeys(Blueprint $table, array $tableNames, array $columnNames): void
    {
        if (!hasForeignKeyExist($table->getTable(), 'role_has_permissions_permission_id_foreign')) {
            $table->foreign($columnNames['permission_pivot_key'] ?? 'permission_id', 'role_has_permissions_permission_id_foreign')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
        }

        if (!hasForeignKeyExist($table->getTable(), 'role_has_permissions_role_id_foreign')) {
            $table->foreign($columnNames['role_pivot_key'] ?? 'role_id', 'role_has_permissions_role_id_foreign')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');
        }
    }

    /**
     * @param Blueprint $table
     * 
     * @return void
     */
    private function removeForeignKeys(Blueprint $table): void
    {
        if (hasForeignKeyExist($table->getTable(), 'role_has_permissions_permission_id_foreign')) {
            $table->dropForeign('role_has_permissions_permission_id_foreign');
        }

        if (hasForeignKeyExist($table->getTable(), 'role_has_permissions_role_id_foreign')) {
            $table->dropForeign('role_has_permissions_role_id_foreign');
        }
    }
};
