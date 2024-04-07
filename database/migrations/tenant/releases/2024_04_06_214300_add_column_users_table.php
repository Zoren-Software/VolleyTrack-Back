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
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'set_password_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('set_password_token')->nullable()->default(null)->after('remember_token');
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
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'set_password_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('set_password_token');
            });
        }
    }
};
