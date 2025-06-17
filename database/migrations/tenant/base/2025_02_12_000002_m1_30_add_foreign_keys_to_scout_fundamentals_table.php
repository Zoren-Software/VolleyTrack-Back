<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('scout_fundamentals_training')) {
            Schema::table('scout_fundamentals_training', function (Blueprint $table) {
                if (!hasAutoIncrement('scout_fundamentals_training')) {
                    DB::statement('ALTER TABLE scout_fundamentals_training MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                }

                if (!hasForeignKeyExist('scout_fundamentals_training', 'scout_fundamentals_training_user_id_foreign')) {
                    $table->foreign('user_id', 'scout_fundamentals_training_user_id_foreign')
                        ->references('id')->on('users')->onDelete('cascade');
                }

                if (!hasForeignKeyExist('scout_fundamentals_training', 'scout_fundamentals_training_player_id_foreign')) {
                    $table->foreign('player_id', 'scout_fundamentals_training_player_id_foreign')
                        ->references('id')->on('users')->onDelete('cascade');
                }

                if (!hasForeignKeyExist('scout_fundamentals_training', 'scout_fundamentals_training_training_id_foreign')) {
                    $table->foreign('training_id', 'scout_fundamentals_training_training_id_foreign')
                        ->references('id')->on('trainings')->onDelete('cascade');
                }

                if (!hasForeignKeyExist('scout_fundamentals_training', 'scout_fundamentals_training_position_id_foreign')) {
                    $table->foreign('position_id', 'scout_fundamentals_training_position_id_foreign')
                        ->references('id')->on('positions')->onDelete('cascade');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('scout_fundamentals_training')) {
            Schema::table('scout_fundamentals_training', function (Blueprint $table) {
                $table->dropForeign('scout_fundamentals_training_user_id_foreign');
                $table->dropForeign('scout_fundamentals_training_player_id_foreign');
                $table->dropForeign('scout_fundamentals_training_training_id_foreign');
                $table->dropForeign('scout_fundamentals_training_position_id_foreign');
            });
        }
    }
};
