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
        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                // Verificar se o campo ID possui AUTO_INCREMENT
                if (!hasAutoIncrement('teams')) {
                    DB::statement("ALTER TABLE teams MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }

                if (!hasForeignKeyExist('teams', 'teams_user_id_foreign')) {
                    $table->foreign('user_id', 'teams_user_id_foreign')
                        ->references('id')
                        ->on('users')
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
        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                if (hasForeignKeyExist('teams', 'teams_user_id_foreign')) {
                    $table->dropForeign('teams_user_id_foreign');
                }
            });
        }
    }
};
