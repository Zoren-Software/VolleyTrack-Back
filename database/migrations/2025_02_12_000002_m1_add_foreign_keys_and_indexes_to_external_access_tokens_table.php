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
            Schema::hasTable('external_access_tokens') &&
            !hasAutoIncrement('external_access_tokens')
        ) {
            DB::statement(
                'ALTER TABLE external_access_tokens MODIFY id BIGINT UNSIGNED AUTO_INCREMENT'
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('external_access_tokens') &&
            hasAutoIncrement('external_access_tokens')
        ) {
            DB::statement(
                'ALTER TABLE external_access_tokens MODIFY id BIGINT UNSIGNED NOT NULL'
            );
        }
    }
};
