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
        if (!Schema::hasTable('teams_users')) {
            Schema::create('teams_users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('team_id');
                $table->unsignedBigInteger('user_id');
                $table->enum('role', ['player', 'technician']);

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
        Schema::dropIfExists('teams_users');
    }
};
