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
        if (Schema::hasTable('positions_users')) {
            Schema::table('positions_users', function (Blueprint $table) {
                if (!hasAutoIncrement('positions_users')) {
                    DB::statement('ALTER TABLE positions_users MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                }

                if (!hasForeignKeyExist('positions_users', 'positions_users_position_id_foreign')) {
                    $table->foreign('position_id', 'positions_users_position_id_foreign')
                        ->references('id')
                        ->on('positions')
                        ->onDelete('cascade');
                }

                if (!hasForeignKeyExist('positions_users', 'positions_users_user_id_foreign')) {
                    $table->foreign('user_id', 'positions_users_user_id_foreign')
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
        if (Schema::hasTable('positions_users')) {
            Schema::table('positions_users', function (Blueprint $table) {
                if (hasForeignKeyExist('positions_users', 'positions_users_position_id_foreign')) {
                    $table->dropForeign('positions_users_position_id_foreign');
                }

                if (hasForeignKeyExist('positions_users', 'positions_users_user_id_foreign')) {
                    $table->dropForeign('positions_users_user_id_foreign');
                }

                if (hasIndexExist('positions_users', 'positions_users_position_id_index')) {
                    $table->dropIndex('positions_users_position_id_index');
                }

                if (hasIndexExist('positions_users', 'positions_users_user_id_index')) {
                    $table->dropIndex('positions_users_user_id_index');
                }
            });
        }
    }
};
