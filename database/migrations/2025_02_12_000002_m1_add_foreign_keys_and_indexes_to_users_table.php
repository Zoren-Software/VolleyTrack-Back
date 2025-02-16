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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!hasAutoIncrement('users')) {
                    DB::statement("ALTER TABLE users MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }
                if (Schema::hasColumn('users', 'email')) {
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
        // Reverter a alteração do AUTO_INCREMENT
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                DB::statement("ALTER TABLE users MODIFY id BIGINT UNSIGNED");
            });
        }
    }
};