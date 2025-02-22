<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        if (Schema::hasTable('languages')) {
            Schema::table('languages', function (Blueprint $table) {
                if (!hasAutoIncrement('languages')) {
                    DB::statement('ALTER TABLE languages MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
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
                if (hasIndexExist('languages', 'languages_slug_unique')) {
                    $table->dropUnique('languages_slug_unique');
                }
                if (hasIndexExist('languages', 'languages_name_unique')) {
                    $table->dropUnique('languages_name_unique');
                }
            });
        }
    }
};
