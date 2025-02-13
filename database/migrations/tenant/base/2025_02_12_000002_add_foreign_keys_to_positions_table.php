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
        if (Schema::hasTable('positions')) {
            Schema::table('positions', function (Blueprint $table) {
                if (!hasAutoIncrement('positions')) {
                    DB::statement("ALTER TABLE positions MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }

                if (!hasForeignKeyExist('positions', 'positions_user_id_foreign')) {
                    $table->foreign('user_id', 'positions_user_id_foreign')
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
        if (Schema::hasTable('positions')) {
            Schema::table('positions', function (Blueprint $table) {
                if (hasForeignKeyExist('positions', 'positions_user_id_foreign')) {
                    $table->dropForeign('positions_user_id_foreign');
                }
            });
        }
    }
};
