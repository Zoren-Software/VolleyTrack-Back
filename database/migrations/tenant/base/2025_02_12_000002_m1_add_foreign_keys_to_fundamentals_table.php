<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    public function up()
    {
        // 🚀 Removendo Foreign Keys antes da alteração
        if (Schema::hasTable('fundamental_specific_fundamental')) {
            Schema::table('fundamental_specific_fundamental', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'fundamental_specific_fundamental_fundamental_id_foreign')) {
                    $table->dropForeign('fundamental_specific_fundamental_fundamental_id_foreign');
                }
            });
        }

        if (Schema::hasTable('fundamentals')) {
            Schema::table('fundamentals', function (Blueprint $table) {
                if (!hasAutoIncrement('fundamentals')) {
                    DB::statement("ALTER TABLE fundamentals MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }

                if (!hasForeignKeyExist('fundamentals', 'fundamentals_user_id_foreign')) {
                    $table->foreign('user_id', 'fundamentals_user_id_foreign')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }
            });
        }

        // 🚀 Recriando a Foreign Key depois da alteração
        if (Schema::hasTable('fundamental_specific_fundamental')) {
            Schema::table('fundamental_specific_fundamental', function (Blueprint $table) {
                if (!hasForeignKeyExist($table->getTable(), 'fundamental_specific_fundamental_fundamental_id_foreign')) {
                    $table->foreign('fundamental_id', 'fundamental_specific_fundamental_fundamental_id_foreign')
                        ->references('id')
                        ->on('fundamentals')
                        ->onDelete('cascade');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('fundamental_specific_fundamental')) {
            Schema::table('fundamental_specific_fundamental', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'fundamental_specific_fundamental_fundamental_id_foreign')) {
                    $table->dropForeign('fundamental_specific_fundamental_fundamental_id_foreign');
                }
            });
        }

        if (Schema::hasTable('fundamentals')) {
            Schema::table('fundamentals', function (Blueprint $table) {
                if (hasForeignKeyExist('fundamentals', 'fundamentals_user_id_foreign')) {
                    $table->dropForeign('fundamentals_user_id_foreign');
                }
            });
        }
    }
};
