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
        if (!Schema::hasTable('team_categories')) {
            Schema::create('team_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('description')
                    ->nullable();
                $table->integer('min_age')
                    ->nullable();
                $table->integer('max_age')
                    ->nullable();
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
        Schema::dropIfExists('team_categories');
    }
};
