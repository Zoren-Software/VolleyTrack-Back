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
        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                if (!hasForeignKeyExist('teams', 'teams_user_id_foreign')) {
                    $table->foreign('user_id', 'teams_user_id_foreign')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }

                if (!hasIndexExist('teams', 'teams_user_id_index')) {
                    $table->index('user_id', 'teams_user_id_index');
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
        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                if (hasForeignKeyExist('teams', 'teams_user_id_foreign')) {
                    $table->dropForeign('teams_user_id_foreign');
                }

                if (hasIndexExist('teams', 'teams_user_id_index')) {
                    $table->dropIndex('teams_user_id_index');
                }
            });
        }
    }
};
