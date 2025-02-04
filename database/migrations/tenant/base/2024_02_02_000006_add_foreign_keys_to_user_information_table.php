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
        if (Schema::hasTable('user_information')) {
            Schema::table('user_information', function (Blueprint $table) {

                // Verificação do AUTO_INCREMENT
                if (!hasAutoIncrement('user_information')) {
                    DB::statement("ALTER TABLE user_information MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }

                if (!hasForeignKeyExist('user_information', 'user_information_user_id_foreign')) {
                    $table->foreign('user_id', 'user_information_user_id_foreign')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }

                if (!hasIndexExist('user_information', 'user_information_user_id_unique')) {
                    $table->unique('user_id', 'user_information_user_id_unique');
                }

                if (!hasIndexExist('user_information', 'user_information_cpf_unique')) {
                    $table->unique('cpf', 'user_information_cpf_unique');
                }

                if (!hasIndexExist('user_information', 'user_information_rg_unique')) {
                    $table->unique('rg', 'user_information_rg_unique');
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
        if (Schema::hasTable('user_information')) {
            Schema::table('user_information', function (Blueprint $table) {
                if (hasForeignKeyExist('user_information', 'user_information_user_id_foreign')) {
                    $table->dropForeign('user_information_user_id_foreign');
                }
            });
        }
    }
};
