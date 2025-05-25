<?php

namespace Tests\Feature\Database\Tenants;

class TeamsTest extends TenantBase
{
    protected string $table = 'teams';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'user_id' => ['type' => 'bigint', 'unsigned' => true],
        'team_category_id' => ['type' => 'bigint', 'unsigned' => true, 'nullable' => true],
        'team_level_id' => ['type' => 'bigint', 'unsigned' => true, 'nullable' => true],
        'name' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define o campo auto_increment

    protected static array $foreignKeys = [
        'teams_user_id_foreign',
        'teams_team_category_id_foreign',
        'teams_team_level_id_foreign',
    ]; // Define as chaves estrangeiras

    protected static array $uniqueKeys = []; // Nenhuma chave única
}
