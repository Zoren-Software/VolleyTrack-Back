<?php

namespace Tests\Feature\Database\Tenants;

class NotificationTypesTest extends TenantBase
{
    protected $table = 'notification_types';

    public static $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'key' => ['type' => 'varchar', 'length' => 50, 'collation' => 'utf8mb4_unicode_ci'],
        'description' => ['type' => 'varchar', 'length' => 100, 'collation' => 'utf8mb4_unicode_ci'],
        'allow_email' => ['type' => 'tinyint'], // boolean
        'allow_system' => ['type' => 'tinyint'], // boolean
        'is_active' => ['type' => 'tinyint'], // boolean
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id'];

    public static $autoIncrements = ['id'];

    public static $foreignKeys = [];

    public static $uniqueKeys = [
        'notification_types_key_unique',
    ];
}
