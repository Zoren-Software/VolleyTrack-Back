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
                $table->unsignedBigInteger('config_id')->index('training_configs_config_id_index');
                $table->unsignedBigInteger('user_id')->index('training_configs_user_id_index');
                $table->smallInteger('days_notification')->default(1);
                $table->boolean('notification_team_by_email')->default(true);
                $table->boolean('notification_technician_by_email')->default(true);
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
