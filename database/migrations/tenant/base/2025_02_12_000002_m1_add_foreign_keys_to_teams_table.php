<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    public function up()
    {
        // 🚀 Removendo a Foreign Key antes da alteração
        if (Schema::hasTable('confirmation_trainings')) {
            Schema::table('confirmation_trainings', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'confirmation_trainings_team_id_foreign')) {
                    $table->dropForeign('confirmation_trainings_team_id_foreign');
                }
            });
        }

        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                // Verificar se o campo ID possui AUTO_INCREMENT
                if (!hasAutoIncrement('teams')) {
                    DB::statement("ALTER TABLE teams MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }

                if (!hasForeignKeyExist('teams', 'teams_user_id_foreign')) {
                    $table->foreign('user_id', 'teams_user_id_foreign')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }
            });
        }

        // 🚀 Recriando a Foreign Key depois da alteração
        if (Schema::hasTable('confirmation_trainings')) {
            Schema::table('confirmation_trainings', function (Blueprint $table) {
                if (!hasForeignKeyExist($table->getTable(), 'confirmation_trainings_team_id_foreign')) {
                    $table->foreign('team_id', 'confirmation_trainings_team_id_foreign')
                        ->references('id')
                        ->on('teams')
                        ->onDelete('cascade');
                }
            });
        }
    }

    public function down()
    {
        // 🚀 Removendo as Foreign Keys antes de desfazer a alteração
        if (Schema::hasTable('confirmation_trainings')) {
            Schema::table('confirmation_trainings', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'confirmation_trainings_team_id_foreign')) {
                    $table->dropForeign('confirmation_trainings_team_id_foreign');
                }
            });
        }

        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'teams_user_id_foreign')) {
                    $table->dropForeign('teams_user_id_foreign');
                }
            });
        }

        // 🚀 Recriando as Foreign Keys depois da reversão
        if (Schema::hasTable('confirmation_trainings')) {
            Schema::table('confirmation_trainings', function (Blueprint $table) {
                if (!hasForeignKeyExist($table->getTable(), 'confirmation_trainings_team_id_foreign')) {
                    $table->foreign('team_id', 'confirmation_trainings_team_id_foreign')
                        ->references('id')
                        ->on('teams')
                        ->onDelete('cascade');
                }
            });
        }
    }
};
