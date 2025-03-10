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
        if (
            Schema::hasTable('websockets_statistics_entries') &&
            !hasAutoIncrement('websockets_statistics_entries')
        ) {
            DB::statement(
                'ALTER TABLE websockets_statistics_entries MODIFY id INT(10) UNSIGNED AUTO_INCREMENT'
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('websockets_statistics_entries') &&
            hasAutoIncrement('websockets_statistics_entries')
        ) {
            DB::statement(
                'ALTER TABLE websockets_statistics_entries MODIFY id INT(10) UNSIGNED NOT NULL'
            );
        }
    }
};
