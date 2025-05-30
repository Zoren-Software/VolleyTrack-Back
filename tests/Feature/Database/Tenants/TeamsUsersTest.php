<?php

namespace Tests\Feature\Database\Tenants;

class TeamsUsersTest extends TenantBase
{
    /**
     * @var string
     */
    protected string $table = 'teams_users';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'team_id' => ['type' => 'bigint', 'unsigned' => true],
        'user_id' => ['type' => 'bigint', 'unsigned' => true],
        'role' => ['type' => 'enum', 'values' => ['player', 'technician'], 'collation' => 'utf8mb4_unicode_ci'],
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
    protected static array $autoIncrements = ['id']; // Define o campo auto_increment

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = [
        'teams_users_user_id_foreign',
        'teams_users_team_id_foreign',
    ]; // Define as chaves estrangeiras

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = []; // Nenhuma chave única
}
