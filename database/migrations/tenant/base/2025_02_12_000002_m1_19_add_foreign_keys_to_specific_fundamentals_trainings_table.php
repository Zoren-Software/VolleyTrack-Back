<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        if (Schema::hasTable('specific_fundamentals_trainings')) {
            Schema::table('specific_fundamentals_trainings', function (Blueprint $table) {
                if (!hasAutoIncrement('specific_fundamentals_trainings')) {
                    DB::statement('ALTER TABLE specific_fundamentals_trainings MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                }

                if (!hasForeignKeyExist('specific_fundamentals_trainings', 'specific_fundamentals_trainings_specific_fundamental_id_foreign')) {
                    $table->foreign('specific_fundamental_id', 'specific_fundamentals_trainings_specific_fundamental_id_foreign')
                        ->references('id')
                        ->on('specific_fundamentals')
                        ->onDelete('cascade');
                }

                if (!hasForeignKeyExist('specific_fundamentals_trainings', 'specific_fundamentals_trainings_training_id_foreign')) {
                    $table->foreign('training_id', 'specific_fundamentals_trainings_training_id_foreign')
                        ->references('id')
                        ->on('trainings')
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
        if (Schema::hasTable('specific_fundamentals_trainings')) {
            Schema::table('specific_fundamentals_trainings', function (Blueprint $table) {
                if (hasForeignKeyExist('specific_fundamentals_trainings', 'specific_fundamentals_trainings_specific_fundamental_id_foreign')) {
                    $table->dropForeign('specific_fundamentals_trainings_specific_fundamental_id_foreign');
                }

                if (hasForeignKeyExist('specific_fundamentals_trainings', 'specific_fundamentals_trainings_training_id_foreign')) {
                    $table->dropForeign('specific_fundamentals_trainings_training_id_foreign');
                }
            });
        }
    }
};
