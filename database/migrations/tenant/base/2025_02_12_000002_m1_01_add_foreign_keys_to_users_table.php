<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 🚀 Alterando a coluna ID da tabela users
        if (
            Schema::hasTable('users') &&
            Schema::hasColumn('users', 'id') &&
            !hasAutoIncrement('users')
        ) {
            DB::statement(
                'ALTER TABLE users MODIFY id BIGINT UNSIGNED AUTO_INCREMENT'
            );
        }

        if (Schema::hasTable('users') && !hasForeignKeyExist('users', 'users_user_id_foreign')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('user_id', 'users_user_id_foreign')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        // 🚀 Alterando a coluna ID da tabela users
        if (
            Schema::hasTable('users') &&
            Schema::hasColumn('users', 'id') &&
            hasAutoIncrement('users')
        ) {
            DB::statement(
                'ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL'
            );
        }

        if (Schema::hasTable('users') && hasForeignKeyExist('users', 'users_user_id_foreign')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign('users_user_id_foreign');
            });
        }
    }
};
