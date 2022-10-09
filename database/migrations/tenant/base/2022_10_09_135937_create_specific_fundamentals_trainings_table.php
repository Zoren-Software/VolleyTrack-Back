<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specific_fundamentals_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specific_fundamental_id')->constrained('specific_fundamentals');
            $table->foreignId('training_id')->constrained('trainings');

            $table->softDeletes();
            $table->timestamps();
        });
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
