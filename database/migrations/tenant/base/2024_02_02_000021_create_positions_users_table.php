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
        if (!Schema::hasTable('positions_users')) {
            Schema::create('positions_users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('position_id')->index('positions_users_position_id_index');
                $table->unsignedBigInteger('user_id')->index('positions_users_user_id_index');

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
        Schema::dropIfExists('positions_users');
    }
};
