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
        if (Schema::hasTable('fundamental_specific_fundamental')) {
            Schema::table('fundamental_specific_fundamental', function (Blueprint $table) {
                if (!hasForeignKeyExist('fundamental_specific_fundamental', 'fundamental_specific_fundamental_fundamental_id_foreign')) {
                    $table->foreign('fundamental_id', 'fundamental_specific_fundamental_fundamental_id_foreign')
                        ->references('id')
                        ->on('fundamentals')
                        ->onDelete('cascade');
                }

                if (!hasForeignKeyExist('fundamental_specific_fundamental', 'fundamental_specific_fundamental_specific_fundamental_id_foreign')) {
                    $table->foreign('specific_fundamental_id', 'fundamental_specific_fundamental_specific_fundamental_id_foreign')
                        ->references('id')
                        ->on('specific_fundamentals')
                        ->onDelete('cascade');
                }

                if (!hasIndexExist('fundamental_specific_fundamental', 'fundamental_specific_fundamental_fundamental_id_index')) {
                    $table->index('fundamental_id', 'fundamental_specific_fundamental_fundamental_id_index');
                }

                if (!hasIndexExist('fundamental_specific_fundamental', 'fundamental_specific_fundamental_specific_fundamental_id_index')) {
                    $table->index('specific_fundamental_id', 'fundamental_specific_fundamental_specific_fundamental_id_index');
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
        if (Schema::hasTable('fundamental_specific_fundamental')) {
            Schema::table('fundamental_specific_fundamental', function (Blueprint $table) {
                if (hasForeignKeyExist('fundamental_specific_fundamental', 'fundamental_specific_fundamental_fundamental_id_foreign')) {
                    $table->dropForeign('fundamental_specific_fundamental_fundamental_id_foreign');
                }

                if (hasForeignKeyExist('fundamental_specific_fundamental', 'fundamental_specific_fundamental_specific_fundamental_id_foreign')) {
                    $table->dropForeign('fundamental_specific_fundamental_specific_fundamental_id_foreign');
                }

                if (hasIndexExist('fundamental_specific_fundamental', 'fundamental_specific_fundamental_fundamental_id_index')) {
                    $table->dropIndex('fundamental_specific_fundamental_fundamental_id_index');
                }

                if (hasIndexExist('fundamental_specific_fundamental', 'fundamental_specific_fundamental_specific_fundamental_id_index')) {
                    $table->dropIndex('fundamental_specific_fundamental_specific_fundamental_id_index');
                }
            });
        }
    }
};
