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
        if (Schema::hasTable('teams_users')) {
            Schema::table('teams_users', function (Blueprint $table) {
                if (!hasAutoIncrement('teams_users')) {
                    DB::statement("ALTER TABLE teams_users MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }

                if (!hasForeignKeyExist('teams_users', 'teams_users_team_id_foreign')) {
                    $table->foreign('team_id', 'teams_users_team_id_foreign')
                        ->references('id')
                        ->on('teams')
                        ->onDelete('cascade');
                }

                if (!hasForeignKeyExist('teams_users', 'teams_users_user_id_foreign')) {
                    $table->foreign('user_id', 'teams_users_user_id_foreign')
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
        if (Schema::hasTable('teams_users')) {
            Schema::table('teams_users', function (Blueprint $table) {
                if (hasForeignKeyExist('teams_users', 'teams_users_team_id_foreign')) {
                    $table->dropForeign('teams_users_team_id_foreign');
                }

                if (hasForeignKeyExist('teams_users', 'teams_users_user_id_foreign')) {
                    $table->dropForeign('teams_users_user_id_foreign');
                }
            });
        }
    }
};
