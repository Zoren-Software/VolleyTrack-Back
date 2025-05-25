<?php

namespace Tests\Feature\Database\Tenants;

class TeamCategoriesTest extends TenantBase
{
    protected string $table = 'team_categories';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'name' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'description' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci', 'nullable' => true],
        'min_age' => ['type' => 'int', 'nullable' => true],
        'max_age' => ['type' => 'int', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id'];

    protected static array $autoIncrements = ['id'];

    protected static array $foreignKeys = [];

    protected static array $uniqueKeys = [];
}
