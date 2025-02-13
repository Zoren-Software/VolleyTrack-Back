<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        if (!Schema::hasTable('confirmation_trainings')) {
            Schema::create('confirmation_trainings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable();
                $table->foreignId('player_id');
                $table->foreignId('training_id');
                $table->foreignId('team_id');
                
                $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
                $table->boolean('presence');

                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('confirmation_trainings');
    }
};
