<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    public function up()
    {
        // 🚀 Removendo a Foreign Key antes da alteração
        if (Schema::hasTable('fundamental_specific_fundamental')) {
            Schema::table('fundamental_specific_fundamental', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'fundamental_specific_fundamental_specific_fundamental_id_foreign')) {
                    $table->dropForeign('fundamental_specific_fundamental_specific_fundamental_id_foreign');
                }
            });
        }

        if (Schema::hasTable('specific_fundamentals')) {
            Schema::table('specific_fundamentals', function (Blueprint $table) {
                if (!hasAutoIncrement('specific_fundamentals')) {
                    DB::statement("ALTER TABLE specific_fundamentals MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }

                if (!hasForeignKeyExist('specific_fundamentals', 'specific_fundamentals_user_id_foreign')) {
                    $table->foreign('user_id', 'specific_fundamentals_user_id_foreign')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }
            });
        }

        // 🚀 Recriando a Foreign Key depois da alteração
        if (Schema::hasTable('fundamental_specific_fundamental')) {
            Schema::table('fundamental_specific_fundamental', function (Blueprint $table) {
                if (!hasForeignKeyExist($table->getTable(), 'fundamental_specific_fundamental_specific_fundamental_id_foreign')) {
                    $table->foreign('specific_fundamental_id', 'fundamental_specific_fundamental_specific_fundamental_id_foreign')
                        ->references('id')
                        ->on('specific_fundamentals')
                        ->onDelete('cascade');
                }
            });
        }
    }

    public function down()
    {
        // 🚀 Removendo as Foreign Keys antes de desfazer a alteração
        if (Schema::hasTable('fundamental_specific_fundamental')) {
            Schema::table('fundamental_specific_fundamental', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'fundamental_specific_fundamental_specific_fundamental_id_foreign')) {
                    $table->dropForeign('fundamental_specific_fundamental_specific_fundamental_id_foreign');
                }
            });
        }

        if (Schema::hasTable('specific_fundamentals')) {
            Schema::table('specific_fundamentals', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'specific_fundamentals_user_id_foreign')) {
                    $table->dropForeign('specific_fundamentals_user_id_foreign');
                }
            });
        }

        // 🚀 Recriando as Foreign Keys depois da reversão
        if (Schema::hasTable('fundamental_specific_fundamental')) {
            Schema::table('fundamental_specific_fundamental', function (Blueprint $table) {
                if (!hasForeignKeyExist($table->getTable(), 'fundamental_specific_fundamental_specific_fundamental_id_foreign')) {
                    $table->foreign('specific_fundamental_id', 'fundamental_specific_fundamental_specific_fundamental_id_foreign')
                        ->references('id')
                        ->on('specific_fundamentals')
                        ->onDelete('cascade');
                }
            });
        }
    }
};
