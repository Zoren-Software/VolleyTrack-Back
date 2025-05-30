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
        if (!Schema::hasTable('languages')) {
            return;
        }

        Schema::table('languages', function (Blueprint $table) {
            $this->modifyAutoIncrement();
            $this->addIndexes($table);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if (!Schema::hasTable('languages')) {
            return;
        }

        Schema::table('languages', function (Blueprint $table) {
            $this->removeIndexes($table);
        });
    }

    /**
     * @return void
     */
    private function modifyAutoIncrement(): void
    {
        if (!hasAutoIncrement('languages')) {
            DB::statement('ALTER TABLE languages MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
        }
    }

    /**
     * @param Blueprint $table
     * 
     * @return void
     */
    private function addIndexes(Blueprint $table): void
    {
        $indexes = [
            'slug' => ['languages_slug_unique', 'languages_slug_index'],
            'name' => ['languages_name_unique', 'languages_name_index'],
        ];

        foreach ($indexes as $column => $keys) {
            foreach ($keys as $indexName) {
                if (!hasIndexExist('languages', $indexName)) {
                    if (strpos($indexName, 'unique') !== false) {
                        $table->unique($column, $indexName);
                    } else {
                        $table->index($column, $indexName);
                    }
                }
            }
        }
    }

    /**
     * @param Blueprint $table
     * 
     * @return void
     */
    private function removeIndexes(Blueprint $table): void
    {
        $indexes = ['languages_slug_index', 'languages_name_index', 'languages_slug_unique', 'languages_name_unique'];

        foreach ($indexes as $indexName) {
            if (hasIndexExist('languages', $indexName)) {
                if (strpos($indexName, 'unique') !== false) {
                    $table->dropUnique($indexName);
                } else {
                    $table->dropIndex($indexName);
                }
            }
        }
    }
};
