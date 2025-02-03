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

        if (Schema::connection($connection)->hasTable($tableName)) {
            Schema::connection($connection)->table($tableName, function (Blueprint $table) use ($tableName) {
                // Índices para subject
                if (!hasIndexExist($tableName, 'subject_index')) {
                    $table->index(['subject_id', 'subject_type'], 'subject_index');
                }

                // Índices para causer
                if (!hasIndexExist($tableName, 'causer_index')) {
                    $table->index(['causer_id', 'causer_type'], 'causer_index');
                }
            });
        }
    }

    public function down(): void
    {
        $connection = config('activitylog.database_connection');
        $tableName = config('activitylog.table_name');

        if (Schema::connection($connection)->hasTable($tableName)) {
            Schema::connection($connection)->table($tableName, function (Blueprint $table) use ($tableName) {
                if (hasIndexExist($tableName, 'subject_index')) {
                    $table->dropIndex('subject_index');
                }
                if (hasIndexExist($tableName, 'causer_index')) {
                    $table->dropIndex('causer_index');
                }
            });
        }
    }
};
