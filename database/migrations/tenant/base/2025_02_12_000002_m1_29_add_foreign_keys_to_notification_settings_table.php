<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('notification_settings')) {
            Schema::table('notification_settings', function (Blueprint $table) {
                if (!hasAutoIncrement('notification_settings')) {
                    DB::statement('ALTER TABLE notification_settings MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                }

                if (!hasForeignKeyExist('notification_settings', 'notification_settings_user_id_foreign')) {
                    $table->foreign('user_id', 'notification_settings_user_id_foreign')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }

                if (!hasForeignKeyExist('notification_settings', 'notification_settings_notification_type_id_foreign')) {
                    $table->foreign('notification_type_id', 'notification_settings_notification_type_id_foreign')
                        ->references('id')
                        ->on('notification_types')
                        ->onDelete('cascade');
                }
            });
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable('notification_settings')) {
            Schema::table('notification_settings', function (Blueprint $table) {
                if (hasForeignKeyExist('notification_settings', 'notification_settings_user_id_foreign')) {
                    $table->dropForeign('notification_settings_user_id_foreign');
                }

                if (hasForeignKeyExist('notification_settings', 'notification_settings_notification_type_id_foreign')) {
                    $table->dropForeign('notification_settings_notification_type_id_foreign');
                }

                if (hasIndexExist('notification_settings', 'notification_settings_user_id_index')) {
                    $table->dropIndex('notification_settings_user_id_index');
                }

                if (hasIndexExist('notification_settings', 'notification_settings_notification_type_id_index')) {
                    $table->dropIndex('notification_settings_notification_type_id_index');
                }
            });
        }
    }
};
