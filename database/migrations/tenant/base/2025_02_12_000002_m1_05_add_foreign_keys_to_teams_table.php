<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                // Verificar se o campo ID possui AUTO_INCREMENT
                if (!hasAutoIncrement('teams')) {
                    DB::statement('ALTER TABLE teams MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
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

    public function down()
    {
        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'teams_user_id_foreign')) {
                    $table->dropForeign('teams_user_id_foreign');
                }
            });
        }
    }
};
