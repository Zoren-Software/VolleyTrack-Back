<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class ActivityLogTest extends TenantBase
{

    protected $table = 'activity_log';

    public static $fields = [
        'id',
        'log_name',
        'description',
        'subject_type',
        'event',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'batch_uuid',
        'created_at',
        'updated_at',
    ];

    public static $fieldTypes = [
        'id'            => ['type' => 'bigint'],
        'log_name'      => ['type' => 'varchar', 'length' => 255],
        'description'   => ['type' => 'text'],
        'subject_type'  => ['type' => 'varchar', 'length' => 255, 'nullable' => true],
        'event'         => ['type' => 'varchar', 'length' => 255, 'nullable' => true],
        'subject_id'    => ['type' => 'bigint', 'nullable' => true],
        'causer_type'   => ['type' => 'varchar', 'length' => 255, 'nullable' => true],
        'causer_id'     => ['type' => 'bigint', 'nullable' => true],
        'properties'    => ['type' => 'longtext', 'nullable' => true],
        'batch_uuid'    => ['type' => 'char', 'length' => 36, 'nullable' => true],
        'created_at'    => ['type' => 'timestamp', 'nullable' => true],
        'updated_at'    => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
    ];

    public static $uniqueKeys = [
    ];

}