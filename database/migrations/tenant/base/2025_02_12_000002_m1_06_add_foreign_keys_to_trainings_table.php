<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {

        if (Schema::hasTable('trainings')) {
            Schema::table('trainings', function (Blueprint $table) {
                if (!hasAutoIncrement('trainings')) {
                    DB::statement('ALTER TABLE trainings MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                }

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

    public function down()
    {
        if (Schema::hasTable('trainings')) {
            Schema::table('trainings', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'trainings_team_id_foreign')) {
                    $table->dropForeign('trainings_team_id_foreign');
                }

                if (hasForeignKeyExist($table->getTable(), 'trainings_user_id_foreign')) {
                    $table->dropForeign('trainings_user_id_foreign');
                }
            });
        }
    }
};
