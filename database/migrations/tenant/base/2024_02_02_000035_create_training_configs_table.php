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
        if (!Schema::hasTable('training_configs')) {
            Schema::create('training_configs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('config_id');
                $table->unsignedBigInteger('user_id');
                $table->smallInteger('days_notification');
                $table->boolean('notification_team_by_email');
                $table->boolean('notification_technician_by_email');
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
        Schema::dropIfExists('training_configs');
    }
};
