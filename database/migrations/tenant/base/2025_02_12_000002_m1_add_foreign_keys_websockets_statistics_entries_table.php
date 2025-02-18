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
        if (Schema::hasTable('websockets_statistics_entries')) {
            if (!hasAutoIncrement('websockets_statistics_entries')) {
                DB::statement('ALTER TABLE websockets_statistics_entries MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
