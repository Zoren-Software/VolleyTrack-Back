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
        if (!Schema::hasTable('specific_fundamentals_trainings')) {
            Schema::create('specific_fundamentals_trainings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('specific_fundamental_id');
                $table->unsignedBigInteger('training_id');

                $table->softDeletes();
                $table->timestamps();
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
        Schema::dropIfExists('specific_fundamentals_trainings');
    }
};
