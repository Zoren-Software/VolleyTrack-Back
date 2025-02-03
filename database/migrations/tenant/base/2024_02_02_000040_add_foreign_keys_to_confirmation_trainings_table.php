<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('confirmation_trainings')) {
            Schema::table('confirmation_trainings', function (Blueprint $table) {
                if (!hasForeignKeyExist('confirmation_trainings', 'confirmation_trainings_user_id_foreign')) {
                    $table->foreign('user_id')
                        ->references('id')
                        ->on('users')
                        ->nullOnDelete();
                }

                if (!hasForeignKeyExist('confirmation_trainings', 'confirmation_trainings_player_id_foreign')) {
                    $table->foreign('player_id')
                        ->references('id')
                        ->on('users')
                        ->cascadeOnDelete();
                }

                if (!hasForeignKeyExist('confirmation_trainings', 'confirmation_trainings_training_id_foreign')) {
                    $table->foreign('training_id')
                        ->references('id')
                        ->on('trainings')
                        ->cascadeOnDelete();
                }

                if (!hasForeignKeyExist('confirmation_trainings', 'confirmation_trainings_team_id_foreign')) {
                    $table->foreign('team_id')
                        ->references('id')
                        ->on('teams')
                        ->cascadeOnDelete();
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
        if (Schema::hasTable('confirmation_trainings')) {
            Schema::table('confirmation_trainings', function (Blueprint $table) {
                if (hasForeignKeyExist('confirmation_trainings', 'confirmation_trainings_user_id_foreign')) {
                    $table->dropForeign('confirmation_trainings_user_id_foreign');
                }

                if (hasForeignKeyExist('confirmation_trainings', 'confirmation_trainings_player_id_foreign')) {
                    $table->dropForeign('confirmation_trainings_player_id_foreign');
                }

                if (hasForeignKeyExist('confirmation_trainings', 'confirmation_trainings_training_id_foreign')) {
                    $table->dropForeign('confirmation_trainings_training_id_foreign');
                }

                if (hasForeignKeyExist('confirmation_trainings', 'confirmation_trainings_team_id_foreign')) {
                    $table->dropForeign('confirmation_trainings_team_id_foreign');
                }
            });
        }
    }
};
