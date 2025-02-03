<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {

            // Verifica e adiciona AUTO_INCREMENT se não existir
            if (!hasAutoIncrement('users')) {
                DB::statement("ALTER TABLE users MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
            }

            Schema::table('users', function (Blueprint $table) {
                if (!hasForeignKeyExist('users', 'users_user_id_foreign')) {
                    $table->foreign('user_id', 'users_user_id_foreign')
                        ->references('id')
                        ->on('users')  // Relacionamento recursivo na própria tabela
                        ->onDelete('cascade');
                }
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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (hasForeignKeyExist('users', 'users_user_id_foreign')) {
                    $table->dropForeign('users_user_id_foreign');
                }
            });
        }
    }
};
