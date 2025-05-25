<?php

namespace Tests\Feature\Database\Tenants;

class TeamLevelsTest extends TenantBase
{
    protected string $table = 'team_levels';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'name' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'description' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id'];

    protected static array $autoIncrements = ['id'];

    protected static array $foreignKeys = [];

    protected static array $uniqueKeys = [];
}
