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

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
    ];

}