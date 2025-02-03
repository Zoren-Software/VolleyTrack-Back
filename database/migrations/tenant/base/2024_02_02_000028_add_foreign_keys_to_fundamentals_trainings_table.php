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
        if (Schema::hasTable('fundamentals_trainings')) {
            Schema::table('fundamentals_trainings', function (Blueprint $table) {
                if (!hasForeignKeyExist('fundamentals_trainings', 'fundamentals_trainings_fundamental_id_foreign')) {
                    $table->foreign('fundamental_id', 'fundamentals_trainings_fundamental_id_foreign')
                        ->references('id')
                        ->on('fundamentals')
                        ->onDelete('cascade');
                }

                if (!hasForeignKeyExist('fundamentals_trainings', 'fundamentals_trainings_training_id_foreign')) {
                    $table->foreign('training_id', 'fundamentals_trainings_training_id_foreign')
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
        if (Schema::hasTable('fundamentals_trainings')) {
            Schema::table('fundamentals_trainings', function (Blueprint $table) {
                if (hasForeignKeyExist('fundamentals_trainings', 'fundamentals_trainings_fundamental_id_foreign')) {
                    $table->dropForeign('fundamentals_trainings_fundamental_id_foreign');
                }

                if (hasForeignKeyExist('fundamentals_trainings', 'fundamentals_trainings_training_id_foreign')) {
                    $table->dropForeign('fundamentals_trainings_training_id_foreign');
                }
            });
        }
    }
};
