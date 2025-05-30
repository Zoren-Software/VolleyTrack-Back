<?php

namespace Tests\Feature\Database\Tenants;

class ConfirmationTrainingsTest extends TenantBase
{
    /**
     * @var string
     */
    protected string $table = 'confirmation_trainings';

    /**
     * @var array<string, mixed>
     */
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

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = ['id']; // Define a chave primária

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = [
        'confirmation_trainings_user_id_foreign',
        'confirmation_trainings_team_id_foreign',
        'confirmation_trainings_training_id_foreign',
        'confirmation_trainings_player_id_foreign',
    ];

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = [];
}
