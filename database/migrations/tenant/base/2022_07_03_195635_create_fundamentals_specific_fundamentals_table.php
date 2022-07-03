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
        Schema::create('fundamentals_specific_fundamentals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fundamental_id');
            $table->unsignedBigInteger('specific_fundamental_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fundamental_id')->references('id')->on('fundamentals');
            $table->foreign('specific_fundamental_id', 'fundamentals_specific_fundamental_id_foreign')->references('id')->on('specific_fundamentals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fundamentals_specific_fundamentals');
    }
};
