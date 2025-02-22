<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!hasAutoIncrement('users')) {
                    DB::statement(
                        'ALTER TABLE users MODIFY id BIGINT UNSIGNED AUTO_INCREMENT'
                    );
                }
                if (
                    Schema::hasColumn('users', 'email') &&
                    !hasIndexExist('users', 'users_email_unique')
                ) {
                    $table->unique('email');
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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (hasAutoIncrement('users')) {
                    DB::statement(
                        'ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL'
                    );
                }
                if (
                    Schema::hasColumn('users', 'email') &&
                    hasIndexExist('users', 'users_email_unique')
                ) {
                    $table->dropUnique('users_email_unique');
                }
            });
        }
    }
};
