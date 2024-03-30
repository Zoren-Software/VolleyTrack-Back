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
        Schema::create('fundamental_specific_fundamental', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fundamental_id')->constrained('fundamentals');
            $table->foreignId('specific_fundamental_id')->constrained('specific_fundamentals');

            $table->timestamps();
            $table->softDeletes();
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
