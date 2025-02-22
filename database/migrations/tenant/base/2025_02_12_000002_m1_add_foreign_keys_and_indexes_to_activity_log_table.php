<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $connection = config('activitylog.database_connection');
        $tableName = config('activitylog.table_name');

        if (!Schema::connection($connection)->hasTable($tableName)) {
            Schema::connection($connection)->table($tableName, function (Blueprint $table) {
                if (!hasIndexExist($table->getTable(), 'activity_log_log_name_index')) {
                    $table->index('log_name', 'activity_log_log_name_index');
                }
            });
        }
    }

    public function down(): void
    {
        $connection = config('activitylog.database_connection');
        $tableName = config('activitylog.table_name');

        Schema::connection($connection)->table($tableName, function (Blueprint $table) {
            if (hasIndexExist($table->getTable(), 'activity_log_log_name_index')) {
                $table->dropIndex('activity_log_log_name_index');
            }
        });
    }
};
