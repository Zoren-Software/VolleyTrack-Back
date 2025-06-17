<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = $this->getPermissionTableNames();

        $this->removeForeignKeys($tableNames);
        $this->modifyPermissionsTable($tableNames);
        $this->recreateForeignKeys($tableNames);
    }

    public function down(): void
    {
        $tableNames = $this->getPermissionTableNames();

        $this->removeForeignKeys($tableNames);
        $this->removeIndexes($tableNames);
        $this->recreateForeignKeys($tableNames);
    }

    /**
     * @param  array<string, string>  $tableNames
     */
    private function removeForeignKeys(array $tableNames): void
    {
        $foreignKeys = [
            'model_has_permissions' => 'model_has_permissions_permission_id_foreign',
            'role_has_permissions' => 'role_has_permissions_permission_id_foreign',
        ];

        foreach ($foreignKeys as $table => $foreignKey) {
            if (Schema::hasTable($tableNames[$table])) {
                Schema::table($tableNames[$table], function (Blueprint $table) use ($foreignKey) {
                    if (hasForeignKeyExist($table->getTable(), $foreignKey)) {
                        $table->dropForeign($foreignKey);
                    }
                });
            }
        }
    }

    /**
     * @param  array<string, string>  $tableNames
     */
    private function modifyPermissionsTable(array $tableNames): void
    {
        if (!Schema::hasTable($tableNames['permissions'])) {
            return;
        }

        Schema::table($tableNames['permissions'], function (Blueprint $table) use ($tableNames) {
            if (!hasAutoIncrement($tableNames['permissions'])) {
                DB::statement("ALTER TABLE {$tableNames['permissions']} MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
            }

            if (!hasIndexExist($tableNames['permissions'], 'permissions_name_guard_name_unique')) {
                $table->unique(['name', 'guard_name'], 'permissions_name_guard_name_unique');
            }
        });
    }

    /**
     * @param  array<string, string>  $tableNames
     */
    private function removeIndexes(array $tableNames): void
    {
        if (!Schema::hasTable($tableNames['permissions'])) {
            return;
        }

        Schema::table($tableNames['permissions'], function (Blueprint $table) use ($tableNames) {
            if (hasIndexExist($tableNames['permissions'], 'permissions_name_guard_name_unique')) {
                $table->dropUnique('permissions_name_guard_name_unique');
            }
        });
    }

    /**
     * @param  array<string, string>  $tableNames
     */
    private function recreateForeignKeys(array $tableNames): void
    {
        $foreignKeys = [
            'model_has_permissions' => 'model_has_permissions_permission_id_foreign',
            'role_has_permissions' => 'role_has_permissions_permission_id_foreign',
        ];

        foreach ($foreignKeys as $table => $foreignKey) {
            if (Schema::hasTable($tableNames[$table])) {
                Schema::table($tableNames[$table], function (Blueprint $table) use ($tableNames, $foreignKey) {
                    if (!hasForeignKeyExist($table->getTable(), $foreignKey)) {
                        $table->foreign('permission_id', $foreignKey)
                            ->references('id')
                            ->on($tableNames['permissions'])
                            ->onDelete('cascade');
                    }
                });
            }
        }
    }

    /**
     * @return array<string, string>
     */
    private function getPermissionTableNames(): array
    {
        $config = config('permission.table_names');

        if (
            !is_array($config)
            || !isset($config['permissions'], $config['model_has_permissions'], $config['role_has_permissions'])
            || !is_string($config['permissions']) || !is_string($config['model_has_permissions']) || !is_string($config['role_has_permissions'])
        ) {
            throw new \RuntimeException('Invalid permission.table_names config.');
        }

        /** @var array<string, string> */
        return $config;
    }
};
