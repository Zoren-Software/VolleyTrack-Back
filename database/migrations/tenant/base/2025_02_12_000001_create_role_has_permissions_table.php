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

        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';

        if (!Schema::hasTable($tableNames['role_has_permissions'])) {
            Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($pivotRole, $pivotPermission) {
                $table->unsignedBigInteger($pivotPermission);
                $table->unsignedBigInteger($pivotRole);

                $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_primary');
            });
        }
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');

        if (!is_array($tableNames)) {
            throw new \RuntimeException('Config "permission.table_names" deve ser um array<string, string>.');
        }

        /** @var array<string, string> $tableNames */
        $tableNames = $tableNames;

        Schema::dropIfExists($tableNames['role_has_permissions']);
    }
};
