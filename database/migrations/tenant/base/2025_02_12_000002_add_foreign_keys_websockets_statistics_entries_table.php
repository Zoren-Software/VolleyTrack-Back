<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('websockets_statistics_entries')) {
            if (!hasAutoIncrement('websockets_statistics_entries')) {
                DB::statement("ALTER TABLE websockets_statistics_entries MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
    }
};
