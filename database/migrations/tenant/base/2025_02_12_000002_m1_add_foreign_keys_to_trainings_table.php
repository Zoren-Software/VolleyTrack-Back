<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        $this->removeForeignKeys();
        $this->modifyTrainingsTable();
        $this->recreateForeignKeys();
    }

    public function down()
    {
        $this->removeForeignKeys();
        $this->removeTrainingsForeignKeys();
        $this->recreateForeignKeys();
    }

    private function removeForeignKeys(): void
    {
        $tables = [
            'confirmation_trainings' => 'confirmation_trainings_training_id_foreign',
            'fundamentals_trainings' => 'fundamentals_trainings_training_id_foreign',
            'specific_fundamentals_trainings' => 'specific_fundamentals_trainings_training_id_foreign',
        ];

        foreach ($tables as $table => $foreignKey) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                    if (hasForeignKeyExist($table->getTable(), $foreignKey)) {
                        $table->dropForeign($foreignKey);
                    }
                });
            }
        }
    }

    private function modifyTrainingsTable(): void
    {
        if (!Schema::hasTable('trainings')) {
            return;
        }

        Schema::table('trainings', function (Blueprint $table) {
            if (!hasAutoIncrement('trainings')) {
                DB::statement('ALTER TABLE trainings MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
            }

            $this->addForeignKey($table, 'team_id', 'teams', 'trainings_team_id_foreign');
            $this->addForeignKey($table, 'user_id', 'users', 'trainings_user_id_foreign');
        });
    }

    private function addForeignKey(Blueprint $table, string $column, string $referenceTable, string $foreignKey): void
    {
        if (!hasForeignKeyExist('trainings', $foreignKey)) {
            $table->foreign($column, $foreignKey)
                ->references('id')
                ->on($referenceTable)
                ->onDelete('cascade');
        }
    }

    private function recreateForeignKeys(): void
    {
        $tables = [
            'confirmation_trainings' => 'confirmation_trainings_training_id_foreign',
            'fundamentals_trainings' => 'fundamentals_trainings_training_id_foreign',
            'specific_fundamentals_trainings' => 'specific_fundamentals_trainings_training_id_foreign',
        ];

        foreach ($tables as $table => $foreignKey) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                    if (!hasForeignKeyExist($table->getTable(), $foreignKey)) {
                        $table->foreign('training_id', $foreignKey)
                            ->references('id')
                            ->on('trainings')
                            ->onDelete('cascade');
                    }
                });
            }
        }
    }

    private function removeTrainingsForeignKeys(): void
    {
        if (!Schema::hasTable('trainings')) {
            return;
        }

        Schema::table('trainings', function (Blueprint $table) {
            $foreignKeys = ['trainings_team_id_foreign', 'trainings_user_id_foreign'];

            foreach ($foreignKeys as $foreignKey) {
                if (hasForeignKeyExist($table->getTable(), $foreignKey)) {
                    $table->dropForeign($foreignKey);
                }
            }
        });
    }
};
