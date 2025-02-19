<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class TeamsUsersTest extends TenantBase
{
    protected $table = 'teams_users';

    public static $fieldTypes = [
        'id'         => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
        'team_id'    => ['type' => 'bigint', 'unsigned' => true],
        'user_id'    => ['type' => 'bigint', 'unsigned' => true],
        'role'       => ['type' => 'enum', 'values' => ['player', 'technician'], 'collation' => 'utf8mb4_unicode_ci'],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define o campo auto_increment

    public static $foreignKeys = [
        'teams_users_user_id_foreign',
        'teams_users_team_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única
}
