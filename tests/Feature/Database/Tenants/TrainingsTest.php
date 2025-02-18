<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class TrainingsTest extends TenantBase
{
    protected $table = 'trainings';

    public static $fields = [
        'id',
        'team_id',
        'user_id',
        'name',
        'description',
        'status',
        'date_start',
        'date_end',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define o campo auto_increment

    public static $foreignKeys = [
        'trainings_user_id_foreign',
        'trainings_team_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única
}
