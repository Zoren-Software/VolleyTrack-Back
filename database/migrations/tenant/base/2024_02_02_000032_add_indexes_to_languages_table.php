<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('languages')) {
            Schema::table('languages', function (Blueprint $table) {
                if (!hasAutoIncrement('languages')) {
                    DB::statement("ALTER TABLE languages MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }
                if (!hasIndexExist('languages', 'languages_slug_unique')) {
                    $table->unique('slug', 'languages_slug_unique');
                }
                if (!hasIndexExist('languages', 'languages_name_unique')) {
                    $table->unique('name', 'languages_name_unique');
                }
                if (!hasIndexExist('languages', 'languages_slug_index')) {
                    $table->index('slug', 'languages_slug_index');
                }
                if (!hasIndexExist('languages', 'languages_name_index')) {
                    $table->index('name', 'languages_name_index');
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
        if (Schema::hasTable('languages')) {
            Schema::table('languages', function (Blueprint $table) {
                if (hasIndexExist('languages', 'languages_slug_index')) {
                    $table->dropIndex('languages_slug_index');
                }
                if (hasIndexExist('languages', 'languages_name_index')) {
                    $table->dropIndex('languages_name_index');
                }
                if (hasIndexExist('languages', 'languages_slug_sunique')) {
                    $table->dropUnique('languages_slug_unique');
                }
                if (hasIndexExist('languages', 'languages_name_unique')) {
                    $table->dropUnique('languages_name_unique');
                }
            });
        }
    }
};
