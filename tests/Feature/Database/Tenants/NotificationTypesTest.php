<?php

namespace Tests\Feature\Database\Tenants;

class NotificationTypesTest extends TenantBase
{
    protected string $table = 'notification_types';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'key' => ['type' => 'varchar', 'length' => 50, 'collation' => 'utf8mb4_unicode_ci'],
        'description' => ['type' => 'varchar', 'length' => 100, 'collation' => 'utf8mb4_unicode_ci'],
        'allow_email' => ['type' => 'tinyint'], // boolean
        'allow_system' => ['type' => 'tinyint'], // boolean
        'is_active' => ['type' => 'tinyint'], // boolean
        'show_list' => ['type' => 'tinyint'], // boolean
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = ['id'];

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = ['id'];

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = [];

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = [
        'notification_types_key_unique',
    ];
}
