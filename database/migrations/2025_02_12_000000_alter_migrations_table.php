<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (
            Schema::hasTable('migrations') &&
            Schema::hasColumn('migrations', 'id') &&
            !hasAutoIncrement('migrations')
        ) {
            DB::statement(
                'ALTER TABLE `migrations` MODIFY COLUMN `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT'
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('migrations') &&
            Schema::hasColumn('migrations', 'id') &&
            hasAutoIncrement('migrations')
        ) {
            DB::statement(
                'ALTER TABLE `migrations` MODIFY COLUMN `id` INT(10) UNSIGNED NOT NULL'
            );
        }
    }
};
