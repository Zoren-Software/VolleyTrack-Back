<?php

namespace Tests\Feature\Database\Tenants;

class TeamsUsersTest extends TenantBase
{
    protected string $table = 'teams_users';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'team_id' => ['type' => 'bigint', 'unsigned' => true],
        'user_id' => ['type' => 'bigint', 'unsigned' => true],
        'role' => ['type' => 'enum', 'values' => ['player', 'technician'], 'collation' => 'utf8mb4_unicode_ci'],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define o campo auto_increment

    protected static array $foreignKeys = [
        'teams_users_user_id_foreign',
        'teams_users_team_id_foreign',
    ]; // Define as chaves estrangeiras

    protected static array $uniqueKeys = []; // Nenhuma chave única
}
