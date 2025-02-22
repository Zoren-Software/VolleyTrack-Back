<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        if (!Schema::hasTable('confirmation_trainings')) {
            return;
        }

        Schema::table('confirmation_trainings', function (Blueprint $table) {
            $this->modifyIdColumn();
            $this->addForeignKeys($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('confirmation_trainings')) {
            return;
        }

        Schema::table('confirmation_trainings', function (Blueprint $table) {
            $this->removeForeignKeys($table);
        });
    }

    private function modifyIdColumn(): void
    {
        if (!hasAutoIncrement('confirmation_trainings')) {
            DB::statement('ALTER TABLE confirmation_trainings MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
        }
    }

    private function addForeignKeys(Blueprint $table): void
    {
        $foreignKeys = [
            'user_id'    => ['users', 'nullOnDelete'],
            'player_id'  => ['users', 'cascadeOnDelete'],
            'training_id'=> ['trainings', 'cascadeOnDelete'],
            'team_id'    => ['teams', 'cascadeOnDelete'],
        ];

        foreach ($foreignKeys as $column => [$referenceTable, $onDelete]) {
            $foreignKeyName = "confirmation_trainings_{$column}_foreign";
            if (!hasForeignKeyExist('confirmation_trainings', $foreignKeyName)) {
                $table->foreign($column)
                    ->references('id')
                    ->on($referenceTable)
                    ->{$onDelete}();
            }
        }
    }

    private function removeForeignKeys(Blueprint $table): void
    {
        $foreignKeys = [
            'user_id',
            'player_id',
            'training_id',
            'team_id',
        ];

        foreach ($foreignKeys as $column) {
            $foreignKeyName = "confirmation_trainings_{$column}_foreign";
            if (hasForeignKeyExist('confirmation_trainings', $foreignKeyName)) {
                $table->dropForeign($foreignKeyName);
            }
        }
    }
};
