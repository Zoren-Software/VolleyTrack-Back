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
                $table->foreignId('user_id')->nullable()->index('confirmation_trainings_user_id_index');
                $table->foreignId('player_id')->index('confirmation_trainings_player_id_index');
                $table->foreignId('training_id')->index('confirmation_trainings_training_id_index');
                $table->foreignId('team_id')->index('confirmation_trainings_team_id_index');
                
                $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
                $table->boolean('presence')->default(false);

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
