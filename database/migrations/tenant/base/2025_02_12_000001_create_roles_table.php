<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        if (!is_array($tableNames)) {
            throw new \RuntimeException('Config "permission.table_names" deve ser um array<string, string>.');
        }

        if (!is_array($columnNames)) {
            throw new \RuntimeException('Config "permission.column_names" deve ser um array<string, string>.');
        }

        if (!is_bool($teams)) {
            throw new \RuntimeException('Config "permission.teams" deve ser boolean.');
        }

        /** @var array<string, string> $tableNames */
        $tableNames = $tableNames;

        /** @var array<string, string> $columnNames */
        $columnNames = $columnNames;

        /** @var bool $teams */
        $teams = $teams;

        if (!Schema::hasTable($tableNames['roles'])) {
            Schema::create($tableNames['roles'], function (Blueprint $table) use ($teams, $columnNames) {
                $table->bigIncrements('id');

                if ($teams) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                    $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
                }

                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');

        if (!is_array($tableNames)) {
            throw new \RuntimeException('Config "permission.table_names" deve ser um array<string, string>.');
        }

        /** @var array<string, string> $tableNames */
        $tableNames = $tableNames;

        Schema::dropIfExists($tableNames['roles']);
    }
};
