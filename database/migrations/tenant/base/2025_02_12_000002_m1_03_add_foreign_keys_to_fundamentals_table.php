<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        if (Schema::hasTable('fundamentals')) {
            Schema::table('fundamentals', function (Blueprint $table) {
                if (!hasAutoIncrement('fundamentals')) {
                    DB::statement('ALTER TABLE fundamentals MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                }

                if (!hasForeignKeyExist('fundamentals', 'fundamentals_user_id_foreign')) {
                    $table->foreign('user_id', 'fundamentals_user_id_foreign')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }
            });
        }

    }

    public function down()
    {
        if (Schema::hasTable('fundamentals')) {
            Schema::table('fundamentals', function (Blueprint $table) {
                if (hasForeignKeyExist('fundamentals', 'fundamentals_user_id_foreign')) {
                    $table->dropForeign('fundamentals_user_id_foreign');
                }
            });
        }
    }
};
