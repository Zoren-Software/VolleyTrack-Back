<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'temporary_password')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('temporary_password')->nullable()->default(null)->after('password');
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
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'temporary_password')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('temporary_password');
            });
        }
    }
};
