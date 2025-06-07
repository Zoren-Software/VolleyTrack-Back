<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $table = config('activitylog.table_name');

        if (!is_string($table)) {
            throw new \RuntimeException('Invalid config for activitylog.table_name. Expected string.');
        }

        if (Schema::hasTable($table) && !hasAutoIncrement($table)) {
            DB::statement("ALTER TABLE {$table} MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table = config('activitylog.table_name');

        if (!is_string($table)) {
            throw new \RuntimeException('Invalid config for activitylog.table_name. Expected string.');
        }

        if (Schema::hasTable($table) && hasAutoIncrement($table)) {
            DB::statement("ALTER TABLE {$table} MODIFY id BIGINT UNSIGNED NOT NULL");
        }
    }
};
