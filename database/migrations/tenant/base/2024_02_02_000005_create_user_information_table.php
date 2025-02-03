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
        if (!Schema::hasTable('user_information')) {
            Schema::create('user_information', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('cpf')->nullable();
                $table->string('phone')->nullable();
                $table->string('rg')->nullable();
                $table->date('birth_date')->nullable();
                $table->timestamps();
                $table->softDeletes();

                // Índices únicos
                $table->unique('user_id', 'user_information_user_id_unique');
                $table->unique('cpf', 'user_information_cpf_unique');
                $table->unique('rg', 'user_information_rg_unique');
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
        Schema::dropIfExists('user_information');
    }
};
