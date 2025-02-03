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
            Schema::connection($connection)->create($tableName, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('log_name')->nullable();
                $table->text('description');
                $table->nullableMorphs('subject');
                $table->string('event')->nullable();
                $table->nullableMorphs('causer');
                $table->json('properties')->nullable();
                $table->uuid('batch_uuid')->nullable();
                $table->timestamps();

                // Criação do índice para a coluna log_name, se não existir
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

        Schema::connection($connection)->dropIfExists($tableName);
    }
};
