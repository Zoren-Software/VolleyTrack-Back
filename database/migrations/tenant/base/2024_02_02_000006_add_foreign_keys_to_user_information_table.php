<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        if (Schema::hasTable('user_information')) {
            Schema::table('user_information', function (Blueprint $table) {
                if (!hasForeignKeyExist('user_information', 'user_information_user_id_foreign')) {
                    $table->foreign('user_id', 'user_information_user_id_foreign')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
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
        if (Schema::hasTable('user_information')) {
            Schema::table('user_information', function (Blueprint $table) {
                if (hasForeignKeyExist('user_information', 'user_information_user_id_foreign')) {
                    $table->dropForeign('user_information_user_id_foreign');
                }
            });
        }
    }
};
