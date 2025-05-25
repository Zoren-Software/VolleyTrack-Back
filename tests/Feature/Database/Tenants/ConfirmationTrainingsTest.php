<?php

namespace Tests\Feature\Database\Tenants;

class ConfirmationTrainingsTest extends TenantBase
{
    protected string $table = 'confirmation_trainings';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'user_id' => ['type' => 'bigint', 'nullable' => true],
        'player_id' => ['type' => 'bigint'],
        'training_id' => ['type' => 'bigint'],
        'team_id' => ['type' => 'bigint'],
        'status' => ['type' => 'enum', 'values' => ['pending', 'confirmed', 'rejected']],
        'presence' => ['type' => 'tinyint'],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    protected static array $foreignKeys = [
        'confirmation_trainings_user_id_foreign',
        'confirmation_trainings_team_id_foreign',
        'confirmation_trainings_training_id_foreign',
        'confirmation_trainings_player_id_foreign',
    ]; // Define as chaves estrangeiras

    protected static array $uniqueKeys = [];
}
