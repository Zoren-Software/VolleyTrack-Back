<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        if (Schema::hasTable('notification_types')) {
            Schema::table('notification_types', function (Blueprint $table) {
                if (!hasIndexExist('notification_types', 'notification_types_key_unique')) {
                    $table->unique('key', 'notification_types_key_unique');
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
        if (Schema::hasTable('notification_types')) {
            Schema::table('notification_types', function (Blueprint $table) {
                if (hasIndexExist('notification_types', 'notification_types_key_unique')) {
                    $table->dropUnique('notification_types_key_unique');
                }
            });
        }
    }
};
