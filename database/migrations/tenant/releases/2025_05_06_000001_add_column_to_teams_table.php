<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * NOTE - Apagável na próxima versão
     */
    public function up(): void
    {
        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {

                if (!Schema::hasColumn('teams', 'team_category_id')) {
                    $table->unsignedBigInteger('team_category_id')
                        ->nullable()
                        ->after('user_id');
                }

                if (!Schema::hasColumn('teams', 'team_level_id')) {
                    $table->unsignedBigInteger('team_level_id')
                        ->nullable()
                        ->after('team_category_id');
                }
            });
        }
    }

    /**
     * NOTE - Apagável na próxima versão
     */
    public function down(): void
    {
        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                if (Schema::hasColumn('teams', 'team_category_id')) {
                    $table->dropColumn('team_category_id');
                }

                if (Schema::hasColumn('teams', 'team_level_id')) {
                    $table->dropColumn('team_level_id');
                }
            });
        }
    }
};
