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
        if (Schema::hasTable('trainings')) {
            Schema::table('trainings', function (Blueprint $table) {
                if (!hasForeignKeyExist('trainings', 'trainings_team_id_foreign')) {
                    $table->foreign('team_id', 'trainings_team_id_foreign')
                        ->references('id')
                        ->on('teams')
                        ->onDelete('cascade');
                }

                if (!hasForeignKeyExist('trainings', 'trainings_user_id_foreign')) {
                    $table->foreign('user_id', 'trainings_user_id_foreign')
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
        if (Schema::hasTable('trainings')) {
            Schema::table('trainings', function (Blueprint $table) {
                if (hasForeignKeyExist('trainings', 'trainings_team_id_foreign')) {
                    $table->dropForeign('trainings_team_id_foreign');
                }

                if (hasForeignKeyExist('trainings', 'trainings_user_id_foreign')) {
                    $table->dropForeign('trainings_user_id_foreign');
                }
            });
        }
    }
};
