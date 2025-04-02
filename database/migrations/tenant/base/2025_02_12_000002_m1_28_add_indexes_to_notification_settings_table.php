<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('notification_settings')) {
            Schema::table('notification_settings', function (Blueprint $table) {
                if (!hasIndexExist('notification_settings', 'notification_settings_user_type_unique')) {
                    $table->unique(['user_id', 'notification_type_id'], 'notification_settings_user_type_unique');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable('notification_settings')) {
            Schema::table('notification_settings', function (Blueprint $table) {
                if (hasIndexExist('notification_settings', 'notification_settings_user_type_unique')) {
                    $table->dropUnique('notification_settings_user_type_unique');
                }
            });
        }
    }
};
