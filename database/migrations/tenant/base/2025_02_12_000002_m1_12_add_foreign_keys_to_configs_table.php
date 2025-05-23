<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        if (Schema::hasTable('configs')) {
            Schema::table('configs', function (Blueprint $table) {
                if (!hasAutoIncrement('configs')) {
                    DB::statement('ALTER TABLE configs MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                }

                if (!hasIndexExist('configs', 'configs_name_tenant_unique')) {
                    $table->unique('name_tenant', 'configs_name_tenant_unique');
                }

                if (!hasForeignKeyExist('configs', 'configs_user_id_foreign')) {
                    $table->foreign('user_id', 'configs_user_id_foreign')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }

                if (!hasForeignKeyExist('configs', 'configs_language_id_foreign')) {
                    $table->foreign('language_id', 'configs_language_id_foreign')
                        ->references('id')
                        ->on('languages')
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
        if (Schema::hasTable('configs')) {
            Schema::table('configs', function (Blueprint $table) {
                if (hasForeignKeyExist('configs', 'configs_user_id_foreign')) {
                    $table->dropForeign('configs_user_id_foreign');
                }

                if (hasForeignKeyExist('configs', 'configs_language_id_foreign')) {
                    $table->dropForeign('configs_language_id_foreign');
                }
            });
        }
    }
};
