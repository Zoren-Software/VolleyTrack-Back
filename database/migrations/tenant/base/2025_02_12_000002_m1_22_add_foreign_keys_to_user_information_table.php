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
        if (!Schema::hasTable('user_information')) {
            return;
        }

        Schema::table('user_information', function (Blueprint $table) {
            $this->modifyAutoIncrement();
            $this->addForeignKey($table);
            $this->addUniqueIndexes($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('user_information')) {
            return;
        }

        Schema::table('user_information', function (Blueprint $table) {
            $this->removeForeignKey($table);
        });
    }

    private function modifyAutoIncrement(): void
    {
        if (!hasAutoIncrement('user_information')) {
            DB::statement('ALTER TABLE user_information MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
        }
    }

    private function addForeignKey(Blueprint $table): void
    {
        if (!hasForeignKeyExist('user_information', 'user_information_user_id_foreign')) {
            $table->foreign('user_id', 'user_information_user_id_foreign')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        }
    }

    private function addUniqueIndexes(Blueprint $table): void
    {
        $indexes = [
            'user_id' => 'user_information_user_id_unique',
            'cpf' => 'user_information_cpf_unique',
            'rg' => 'user_information_rg_unique',
        ];

        foreach ($indexes as $column => $indexName) {
            if (!hasIndexExist('user_information', $indexName)) {
                $table->unique($column, $indexName);
            }
        }
    }

    private function removeForeignKey(Blueprint $table): void
    {
        if (hasForeignKeyExist('user_information', 'user_information_user_id_foreign')) {
            $table->dropForeign('user_information_user_id_foreign');
        }
    }
};
