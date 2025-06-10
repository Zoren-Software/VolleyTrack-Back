<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $connectionRaw = config('activitylog.database_connection');
        $connection = is_string($connectionRaw) || is_null($connectionRaw)
            ? $connectionRaw
            : throw new \RuntimeException('Config "activitylog.database_connection" must be string|null.');

        $tableName = config('activitylog.table_name');

        if (!is_string($tableName)) {
            throw new \RuntimeException('Config "activitylog.table_name" must be a string.');
        }

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
            });
        }
    }

    public function down(): void
    {
        $connectionRaw = config('activitylog.database_connection');
        $connection = is_string($connectionRaw) || is_null($connectionRaw)
            ? $connectionRaw
            : throw new \RuntimeException('Config "activitylog.database_connection" must be string|null.');

        $tableName = config('activitylog.table_name');

        if (!is_string($tableName)) {
            throw new \RuntimeException('Config "activitylog.table_name" must be a string.');
        }

        Schema::connection($connection)->dropIfExists($tableName);
    }
};
