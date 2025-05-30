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
        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                // Verificar se o campo ID possui AUTO_INCREMENT
                if (!hasAutoIncrement('teams')) {
                    DB::statement('ALTER TABLE teams MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                }

                if (!hasForeignKeyExist('teams', 'teams_user_id_foreign')) {
                    $table->foreign('user_id', 'teams_user_id_foreign')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }
                if (
                    !hasForeignKeyExist('teams', 'teams_team_category_id_foreign') &&
                    Schema::hasColumn('teams', 'team_category_id')
                ) {
                    $table->foreign('team_category_id')
                        ->references('id')
                        ->on('team_categories')
                        ->nullOnDelete();
                }
                if (!hasForeignKeyExist('teams', 'teams_team_level_id_foreign') &&
                    Schema::hasColumn('teams', 'team_level_id')
                ) {
                    $table->foreign('team_level_id')
                        ->references('id')
                        ->on('team_levels')
                        ->nullOnDelete();
                }
            });
        }

    }

    /**
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'teams_user_id_foreign')) {
                    $table->dropForeign('teams_user_id_foreign');
                }
            });
        }
    }
};
