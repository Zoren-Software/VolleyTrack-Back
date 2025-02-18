<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('failed_jobs')) {
            Schema::table('failed_jobs', function (Blueprint $table) {
                if (!hasAutoIncrement('failed_jobs')) {
                    DB::statement('ALTER TABLE failed_jobs MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                }
                if (Schema::hasColumn('failed_jobs', 'uuid') && !hasIndexExist('failed_jobs', 'failed_jobs_uuid_unique')) {
                    $table->unique('uuid');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverter a alteração do AUTO_INCREMENT
        if (Schema::hasTable('failed_jobs')) {
            Schema::table('failed_jobs', function (Blueprint $table) {
                DB::statement('ALTER TABLE failed_jobs MODIFY id BIGINT UNSIGNED');
            });
        }
    }
};
