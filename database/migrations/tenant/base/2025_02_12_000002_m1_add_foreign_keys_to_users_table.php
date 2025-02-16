<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    public function up()
    {
        // 🚀 Lista de tabelas e suas Foreign Keys
        $foreignKeys = [
            'configs' => 'configs_user_id_foreign',
            'fundamentals' => 'fundamentals_user_id_foreign',
            'positions' => 'positions_user_id_foreign',
            'positions_users' => 'positions_users_user_id_foreign',
            'specific_fundamentals' => 'specific_fundamentals_user_id_foreign',
            'teams' => 'teams_user_id_foreign',
            'teams_users' => 'teams_users_user_id_foreign',
            'training_configs' => 'training_configs_user_id_foreign',
            'trainings' => 'trainings_user_id_foreign',
            'user_information' => 'user_information_user_id_foreign',
            'confirmation_trainings' => 'confirmation_trainings_user_id_foreign',
            'confirmation_trainings' => 'confirmation_trainings_player_id_foreign',
        ];

        // 🚀 Desativar temporariamente as Foreign Keys no MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 🚀 Removendo Foreign Keys existentes
        foreach ($foreignKeys as $table => $foreignKey) {
            if ($this->foreignKeyExists($table, $foreignKey)) {
                try {
                    DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$foreignKey}");
                    dump("Removida FK: {$foreignKey} de {$table}");
                } catch (\Exception $e) {
                    dump("Erro ao remover FK {$foreignKey} de {$table}: " . $e->getMessage());
                }
            }
        }

        // 🚀 Alterando a coluna ID da tabela users
        if (Schema::hasTable('users')) {
            if (!hasAutoIncrement('users')) {
                DB::statement("ALTER TABLE users MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                dump("Alterado users.id para BIGINT UNSIGNED AUTO_INCREMENT");
            }
        }

        // 🚀 Reativar as Foreign Keys no MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🚀 Recriando Foreign Keys (somente se não existirem)
        foreach ($foreignKeys as $table => $foreignKey) {
            if (Schema::hasTable($table) && !$this->foreignKeyExists($table, $foreignKey)) {
                Schema::table($table, function (Blueprint $tableB) use ($foreignKey, $table) {
                    dump("Recriando FK: {$foreignKey} em {$table}");
                    $tableB->foreign('user_id', $foreignKey)
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                });
            } else {
                dump("FK já existe: {$foreignKey} em {$table}, pulando recriação.");
            }
        }
    }

    public function down()
    {
        // 🚀 Lista de Foreign Keys para remoção no rollback
        $foreignKeys = [
            'configs' => 'configs_user_id_foreign',
            'fundamentals' => 'fundamentals_user_id_foreign',
            'positions' => 'positions_user_id_foreign',
            'positions_users' => 'positions_users_user_id_foreign',
            'specific_fundamentals' => 'specific_fundamentals_user_id_foreign',
            'teams' => 'teams_user_id_foreign',
            'teams_users' => 'teams_users_user_id_foreign',
            'training_configs' => 'training_configs_user_id_foreign',
            'trainings' => 'trainings_user_id_foreign',
            'user_information' => 'user_information_user_id_foreign',
            'confirmation_trainings' => 'confirmation_trainings_user_id_foreign',
            'confirmation_trainings' => 'confirmation_trainings_player_id_foreign',
        ];

        // 🚀 Desativar temporariamente as Foreign Keys no MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 🚀 Removendo Foreign Keys existentes antes do rollback
        foreach ($foreignKeys as $table => $foreignKey) {
            if ($this->foreignKeyExists($table, $foreignKey)) {
                try {
                    DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$foreignKey}");
                    dump("Removida FK: {$foreignKey} de {$table} (rollback)");
                } catch (\Exception $e) {
                    dump("Erro ao remover FK {$foreignKey} de {$table} (rollback): " . $e->getMessage());
                }
            }
        }

        // 🚀 Reativar as Foreign Keys no MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🚀 Recriando Foreign Keys depois do rollback
        foreach ($foreignKeys as $table => $foreignKey) {
            if (Schema::hasTable($table) && !$this->foreignKeyExists($table, $foreignKey)) {
                Schema::table($table, function (Blueprint $tableB) use ($foreignKey, $table) {
                    dump("Recriando FK: {$foreignKey} em {$table} (rollback)");
                    $tableB->foreign('user_id', $foreignKey)
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                });
            } else {
                dump("FK já existe: {$foreignKey} em {$table}, pulando recriação.");
            }
        }
    }

    /**
     * Verifica se a chave estrangeira existe na tabela.
     *
     * @param string $table Nome da tabela
     * @param string $foreignKey Nome da chave estrangeira
     * @return bool Retorna true se a FK existir, false se não existir
     */
    private function foreignKeyExists(string $table, string $foreignKey): bool
    {
        if (!Schema::hasTable($table)) {
            return false;
        }

        $result = DB::select("SHOW CREATE TABLE {$table}");
        if (!isset($result[0]->{'Create Table'})) {
            return false;
        }

        return strpos($result[0]->{'Create Table'}, "`{$foreignKey}`") !== false;
    }
};
