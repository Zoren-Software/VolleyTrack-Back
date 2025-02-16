<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    public function up()
    {
        // 🚀 Removendo Foreign Keys antes da alteração
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
            'confirmation_trainings' => 'confirmation_trainings_player_id_foreign',
            'user_information' => 'user_information_user_id_foreign',
            'confirmation_trainings' => 'confirmation_trainings_user_id_foreign',
        ];

        foreach ($foreignKeys as $table => $foreignKey) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                    if (hasForeignKeyExist($table->getTable(), $foreignKey)) {
                        $table->dropForeign($foreignKey);
                    }
                });
            }
        }

        if (Schema::hasTable('users')) {
            // 🚀 Alterando a coluna ID
            if (!hasAutoIncrement('users')) {
                DB::statement("ALTER TABLE users MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
            }

            Schema::table('users', function (Blueprint $table) {
                if (!hasForeignKeyExist('users', 'users_user_id_foreign')) {
                    $table->foreign('user_id', 'users_user_id_foreign')
                        ->references('id')
                        ->on('users')  // Relacionamento recursivo na própria tabela
                        ->onDelete('cascade');
                }

                if (!hasIndexExist('users', 'users_email_unique')) {
                    $table->unique('email', 'users_email_unique');
                }
            });
        }

        // 🚀 Recriando as Foreign Keys depois da alteração
        foreach ($foreignKeys as $table => $foreignKey) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                    if (!hasForeignKeyExist($table->getTable(), $foreignKey)) {
                        $table->foreign('user_id', $foreignKey)
                            ->references('id')
                            ->on('users')
                            ->onDelete('cascade');
                    }
                });
            }
        }
    }

    public function down()
    {
        // 🚀 Removendo as Foreign Keys antes de desfazer a alteração
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
            'confirmation_trainings' => 'confirmation_trainings_player_id_foreign',
            'user_information' => 'user_information_user_id_foreign',
            'confirmation_trainings' => 'confirmation_trainings_user_id_foreign',

        ];

        foreach ($foreignKeys as $table => $foreignKey) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                    if (hasForeignKeyExist($table->getTable(), $foreignKey)) {
                        $table->dropForeign($foreignKey);
                    }
                });
            }
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (hasForeignKeyExist($table->getTable(), 'users_user_id_foreign')) {
                    $table->dropForeign('users_user_id_foreign');
                }
            });
        }

        // 🚀 Recriando as Foreign Keys depois da reversão
        foreach ($foreignKeys as $table => $foreignKey) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                    if (!hasForeignKeyExist($table->getTable(), $foreignKey)) {
                        $table->foreign('user_id', $foreignKey)
                            ->references('id')
                            ->on('users')
                            ->onDelete('cascade');
                    }
                });
            }
        }
    }
};
