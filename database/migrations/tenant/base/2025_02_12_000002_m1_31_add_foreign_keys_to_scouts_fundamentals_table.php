<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $tables = [
        'scouts_defense',
        'scouts_reception',
        'scouts_serve',
        'scouts_attack',
        'scouts_block',
        'scouts_set_assist',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use (&$tableName) {
                    $fkUser = $tableName . '_user_id_foreign';
                    $fkScout = $tableName . '_scout_fundamental_id_foreign';

                    if (!hasForeignKeyExist($tableName, $fkUser)) {
                        $table->foreign('user_id', $fkUser)
                            ->references('id')->on('users')->onDelete('cascade');
                    }

                    if (!hasForeignKeyExist($tableName, $fkScout)) {
                        $table->foreign('scout_fundamental_id', $fkScout)
                            ->references('id')->on('scout_fundamentals')->onDelete('cascade');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use (&$tableName) {
                    $fkUser = $tableName . '_user_id_foreign';
                    $fkScout = $tableName . '_scout_fundamental_id_foreign';

                    if (hasForeignKeyExist($tableName, $fkUser)) {
                        $table->dropForeign($fkUser);
                    }

                    if (hasForeignKeyExist($tableName, $fkScout)) {
                        $table->dropForeign($fkScout);
                    }
                });
            }
        }
    }
};
