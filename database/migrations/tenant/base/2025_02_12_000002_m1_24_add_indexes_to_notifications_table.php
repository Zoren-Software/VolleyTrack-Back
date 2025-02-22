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
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                if (!hasIndexExist('notifications', 'notifications_notifiable_id_index')) {
                    $table->index('notifiable_id', 'notifications_notifiable_id_index');
                }

                if (!hasIndexExist('notifications', 'notifications_notifiable_type_index')) {
                    $table->index('notifiable_type', 'notifications_notifiable_type_index');
                }

                // Adição do índice composto (notifiable_type + notifiable_id)
                if (!hasIndexExist('notifications', 'notifications_notifiable_type_notifiable_id_index')) {
                    $table->index(['notifiable_type', 'notifiable_id'], 'notifications_notifiable_type_notifiable_id_index');
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
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                if (hasIndexExist('notifications', 'notifications_notifiable_id_index')) {
                    $table->dropIndex('notifications_notifiable_id_index');
                }

                if (hasIndexExist('notifications', 'notifications_notifiable_type_index')) {
                    $table->dropIndex('notifications_notifiable_type_index');
                }

                // Remover o índice composto (notifiable_type + notifiable_id)
                if (hasIndexExist('notifications', 'notifications_notifiable_type_notifiable_id_index')) {
                    $table->dropIndex('notifications_notifiable_type_notifiable_id_index');
                }
            });
        }
    }
};
