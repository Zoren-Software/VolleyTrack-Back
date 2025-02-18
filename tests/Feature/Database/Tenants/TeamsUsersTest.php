<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class TeamsUsersTest extends TenantBase
{
    protected $table = 'teams_users';

    public static $fields = [
        'id',
        'team_id',
        'user_id',
        'role',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define o campo auto_increment

    public static $foreignKeys = [
        'teams_users_user_id_foreign',
        'teams_users_team_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única
}
