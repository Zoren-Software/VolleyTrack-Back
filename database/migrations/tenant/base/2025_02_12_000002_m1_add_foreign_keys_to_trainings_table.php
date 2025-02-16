<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    public function up()
    {
        // 🚀 Removendo Foreign Keys antes da alteração
        if (Schema::hasTable('confirmation_trainings')) {
            Schema::table('confirmation_trainings', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'confirmation_trainings_training_id_foreign')) {
                    $table->dropForeign('confirmation_trainings_training_id_foreign');
                }
            });
        }

        if (Schema::hasTable('fundamentals_trainings')) {
            Schema::table('fundamentals_trainings', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'fundamentals_trainings_training_id_foreign')) {
                    $table->dropForeign('fundamentals_trainings_training_id_foreign');
                }
            });
        }

        if (Schema::hasTable('specific_fundamentals_trainings')) {
            Schema::table('specific_fundamentals_trainings', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'specific_fundamentals_trainings_training_id_foreign')) {
                    $table->dropForeign('specific_fundamentals_trainings_training_id_foreign');
                }
            });
        }

        if (Schema::hasTable('trainings')) {
            Schema::table('trainings', function (Blueprint $table) {
                if (!hasAutoIncrement('trainings')) {
                    DB::statement("ALTER TABLE trainings MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
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

        // 🚀 Recriando as Foreign Keys depois da alteração
        if (Schema::hasTable('confirmation_trainings')) {
            Schema::table('confirmation_trainings', function (Blueprint $table) {
                if (!hasForeignKeyExist($table->getTable(), 'confirmation_trainings_training_id_foreign')) {
                    $table->foreign('training_id', 'confirmation_trainings_training_id_foreign')
                        ->references('id')
                        ->on('trainings')
                        ->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable('fundamentals_trainings')) {
            Schema::table('fundamentals_trainings', function (Blueprint $table) {
                if (!hasForeignKeyExist($table->getTable(), 'fundamentals_trainings_training_id_foreign')) {
                    $table->foreign('training_id', 'fundamentals_trainings_training_id_foreign')
                        ->references('id')
                        ->on('trainings')
                        ->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable('specific_fundamentals_trainings')) {
            Schema::table('specific_fundamentals_trainings', function (Blueprint $table) {
                if (!hasForeignKeyExist($table->getTable(), 'specific_fundamentals_trainings_training_id_foreign')) {
                    $table->foreign('training_id', 'specific_fundamentals_trainings_training_id_foreign')
                        ->references('id')
                        ->on('trainings')
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
                if (hasForeignKeyExist($table->getTable(), 'confirmation_trainings_training_id_foreign')) {
                    $table->dropForeign('confirmation_trainings_training_id_foreign');
                }
            });
        }

        if (Schema::hasTable('fundamentals_trainings')) {
            Schema::table('fundamentals_trainings', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'fundamentals_trainings_training_id_foreign')) {
                    $table->dropForeign('fundamentals_trainings_training_id_foreign');
                }
            });
        }

        if (Schema::hasTable('specific_fundamentals_trainings')) {
            Schema::table('specific_fundamentals_trainings', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'specific_fundamentals_trainings_training_id_foreign')) {
                    $table->dropForeign('specific_fundamentals_trainings_training_id_foreign');
                }
            });
        }

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

        // 🚀 Recriando as Foreign Keys depois da reversão
        if (Schema::hasTable('confirmation_trainings')) {
            Schema::table('confirmation_trainings', function (Blueprint $table) {
                if (!hasForeignKeyExist($table->getTable(), 'confirmation_trainings_training_id_foreign')) {
                    $table->foreign('training_id', 'confirmation_trainings_training_id_foreign')
                        ->references('id')
                        ->on('trainings')
                        ->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable('fundamentals_trainings')) {
            Schema::table('fundamentals_trainings', function (Blueprint $table) {
                if (!hasForeignKeyExist($table->getTable(), 'fundamentals_trainings_training_id_foreign')) {
                    $table->foreign('training_id', 'fundamentals_trainings_training_id_foreign')
                        ->references('id')
                        ->on('trainings')
                        ->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable('specific_fundamentals_trainings')) {
            Schema::table('specific_fundamentals_trainings', function (Blueprint $table) {
                if (!hasForeignKeyExist($table->getTable(), 'specific_fundamentals_trainings_training_id_foreign')) {
                    $table->foreign('training_id', 'specific_fundamentals_trainings_training_id_foreign')
                        ->references('id')
                        ->on('trainings')
                        ->onDelete('cascade');
                }
            });
        }
    }
};
