<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class ConfirmationTrainingsTest extends TenantBase
{
    protected $table = 'confirmation_trainings';

    public static $fields = [
        'id',
        'user_id',
        'player_id',
        'training_id',
        'team_id',
        'status',
        'presence',
        'created_at',
        'updated_at',
        'deleted_at',
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
