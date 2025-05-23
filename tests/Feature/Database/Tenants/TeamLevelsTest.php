<?php

namespace Tests\Feature\Database\Tenants;

class TeamLevelsTest extends TenantBase
{
    protected $table = 'team_levels';

    public static $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'name' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'description' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id'];

    public static $autoIncrements = ['id'];

    public static $foreignKeys = [];

    public static $uniqueKeys = [];
}
