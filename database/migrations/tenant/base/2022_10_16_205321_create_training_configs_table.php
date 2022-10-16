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
        Schema::create('training_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('config_id')->constrained('configs');
            $table->foreignId('user_id')->constrained('users');
            $table->smallInteger('days_notification')->default(1);
            //create a column boolean notification team by email
            $table->boolean('notification_team_by_email')->default(true);
            $table->boolean('notification_technician_by_email')->default(true);
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
        Schema::dropIfExists('training_configs');
    }
};
