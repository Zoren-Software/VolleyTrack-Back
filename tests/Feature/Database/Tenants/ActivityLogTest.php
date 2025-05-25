<?php

namespace Tests\Feature\Database\Tenants;

class ActivityLogTest extends TenantBase
{
    protected string $table = 'activity_log';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'log_name' => ['type' => 'varchar', 'length' => 255, 'nullable' => true],
        'description' => ['type' => 'text'],
        'subject_type' => ['type' => 'varchar', 'length' => 255, 'nullable' => true],
        'event' => ['type' => 'varchar', 'length' => 255, 'nullable' => true],
        'subject_id' => ['type' => 'bigint', 'nullable' => true],
        'causer_type' => ['type' => 'varchar', 'length' => 255, 'nullable' => true],
        'causer_id' => ['type' => 'bigint', 'nullable' => true],
        'properties' => ['type' => 'longtext', 'nullable' => true],
        'batch_uuid' => ['type' => 'char', 'length' => 36, 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    protected static array $foreignKeys = [
    ];

    protected static array $uniqueKeys = [
    ];
}
