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
        if (Schema::hasTable('positions')) {
            Schema::table('positions', function (Blueprint $table) {
                if (!hasForeignKeyExist('positions', 'positions_user_id_foreign')) {
                    $table->foreign('user_id', 'positions_user_id_foreign')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }

                if (!hasIndexExist('positions', 'positions_user_id_index')) {
                    $table->index('user_id', 'positions_user_id_index');
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
        if (Schema::hasTable('positions')) {
            Schema::table('positions', function (Blueprint $table) {
                if (hasForeignKeyExist('positions', 'positions_user_id_foreign')) {
                    $table->dropForeign('positions_user_id_foreign');
                }

                if (hasIndexExist('positions', 'positions_user_id_index')) {
                    $table->dropIndex('positions_user_id_index');
                }
            });
        }
    }
};
