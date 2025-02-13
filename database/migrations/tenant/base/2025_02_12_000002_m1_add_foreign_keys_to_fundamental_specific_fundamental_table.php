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
        if (Schema::hasTable('fundamental_specific_fundamental')) {
            Schema::table('fundamental_specific_fundamental', function (Blueprint $table) {
                if (!hasAutoIncrement('fundamental_specific_fundamental')) {
                    DB::statement("ALTER TABLE fundamental_specific_fundamental MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }

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
            });
        }
    }
};
