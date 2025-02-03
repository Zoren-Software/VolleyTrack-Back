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
        if (Schema::hasTable('training_configs')) {
            Schema::table('training_configs', function (Blueprint $table) {
                if (!hasForeignKeyExist('training_configs', 'training_configs_config_id_foreign')) {
                    $table->foreign('config_id', 'training_configs_config_id_foreign')
                        ->references('id')
                        ->on('configs')
                        ->onDelete('cascade');
                }

                if (!hasForeignKeyExist('training_configs', 'training_configs_user_id_foreign')) {
                    $table->foreign('user_id', 'training_configs_user_id_foreign')
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
        if (Schema::hasTable('training_configs')) {
            Schema::table('training_configs', function (Blueprint $table) {
                if (hasForeignKeyExist('training_configs', 'training_configs_config_id_foreign')) {
                    $table->dropForeign('training_configs_config_id_foreign');
                }

                if (hasForeignKeyExist('training_configs', 'training_configs_user_id_foreign')) {
                    $table->dropForeign('training_configs_user_id_foreign');
                }
            });
        }
    }
};
