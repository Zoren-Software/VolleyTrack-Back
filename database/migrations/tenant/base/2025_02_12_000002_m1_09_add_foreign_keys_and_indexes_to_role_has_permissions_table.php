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

        if (!is_array($tableNames)) {
            throw new \RuntimeException('Config "permission.table_names" deve ser um array<string, string>.');
        }

        if (!is_array($columnNames)) {
            throw new \RuntimeException('Config "permission.column_names" deve ser um array<string, string>.');
        }

        /** @var array<string, string> $tableNames */
        $tableNames = $tableNames;

        /** @var array<string, string> $columnNames */
        $columnNames = $columnNames;

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

        if (!is_array($tableNames)) {
            throw new \RuntimeException('Config "permission.table_names" deve ser um array<string, string>.');
        }

        /** @var array<string, string> $tableNames */
        $tableNames = $tableNames;

        if (!Schema::hasTable($tableNames['role_has_permissions'])) {
            return;
        }

        Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) {
            $this->removeForeignKeys($table);
        });
    }

    /**
     * @param  array<string, string>  $tableNames
     * @param  array<string, string>  $columnNames
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
