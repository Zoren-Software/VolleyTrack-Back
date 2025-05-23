<?php

namespace Tests\Feature\Database\Tenants;

class TeamCategoriesTest extends TenantBase
{
    protected $table = 'team_categories';

    public static $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'name' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'description' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci', 'nullable' => true],
        'min_age' => ['type' => 'int', 'nullable' => true],
        'max_age' => ['type' => 'int', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id'];

    public static $autoIncrements = ['id'];

    public static $foreignKeys = [];

    public static $uniqueKeys = [];
}
