<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('external_access_tokens')) {
            Schema::table('external_access_tokens', function (Blueprint $table) {
                if (!hasAutoIncrement('external_access_tokens')) {
                    DB::statement('ALTER TABLE external_access_tokens MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('external_access_tokens')) {
            Schema::table('external_access_tokens', function (Blueprint $table) {
                DB::statement('ALTER TABLE external_access_tokens MODIFY id BIGINT UNSIGNED');
            });
        }
    }
};
