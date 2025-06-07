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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')
                    ->nullable()
                    ->default(null); // Chave estrangeira será adicionada depois
                $table->string('name');
                $table->string('email');
                $table->timestamp('email_verified_at')
                    ->nullable();
                $table->string('password')
                    ->nullable()
                    ->default(null);
                $table->rememberToken();
                $table->string('set_password_token')
                    ->nullable()
                    ->default(null);
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
        Schema::dropIfExists('users');
    }
};
