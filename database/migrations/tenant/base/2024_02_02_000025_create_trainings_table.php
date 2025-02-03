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
        if (!Schema::hasTable('trainings')) {
            Schema::create('trainings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('team_id')->index('trainings_team_id_index');
                $table->unsignedBigInteger('user_id')->index('trainings_user_id_index');

                $table->string('name');
                $table->text('description')->nullable();
                $table->boolean('status')->default(true);
                $table->dateTimeTz('date_start');
                $table->dateTimeTz('date_end');

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
        Schema::dropIfExists('trainings');
    }
};
