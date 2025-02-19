<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class ConfirmationTrainingsTest extends TenantBase
{
    protected $table = 'confirmation_trainings';

    public static $fieldTypes = [
        'id'           => ['type' => 'bigint'],
        'user_id'      => ['type' => 'bigint', 'nullable' => true],
        'player_id'    => ['type' => 'bigint'],
        'training_id'  => ['type' => 'bigint'],
        'team_id'      => ['type' => 'bigint'],
        'status'       => ['type' => 'enum', 'values' => ['pending', 'confirmed', 'rejected']],
        'presence'     => ['type' => 'tinyint'],
        'created_at'   => ['type' => 'timestamp', 'nullable' => true],
        'updated_at'   => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at'   => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'confirmation_trainings_user_id_foreign',
        'confirmation_trainings_team_id_foreign',
        'confirmation_trainings_training_id_foreign',
        'confirmation_trainings_player_id_foreign',
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = [];
}
