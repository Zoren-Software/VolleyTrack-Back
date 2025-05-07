<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * NOTE - Apagável na próxima versão
     * 
     * @return [type]
     */
    public function up()
    {
        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                if (
                    !hasForeignKeyExist('teams', 'teams_team_category_id_foreign') && 
                    Schema::hasColumn('teams', 'team_category_id')
                ) {
                    $table->foreign('team_category_id')
                        ->references('id')
                        ->on('team_categories')
                        ->nullOnDelete();
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('teams')) {
            Schema::table('teams', function (Blueprint $table) {
                if (hasForeignKeyExist('teams', 'teams_team_category_id_foreign') &&
                    Schema::hasColumn('teams', 'team_category_id')
                ) {
                    $table->dropForeign('teams_team_category_id_foreign');
                }
            });
        }
    }
};
