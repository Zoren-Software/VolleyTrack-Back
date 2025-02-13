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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('specific_fundamentals')) {
            Schema::table('specific_fundamentals', function (Blueprint $table) {
                if (hasForeignKeyExist('specific_fundamentals', 'specific_fundamentals_user_id_foreign')) {
                    $table->dropForeign('specific_fundamentals_user_id_foreign');
                }
            });
        }
    }
};
