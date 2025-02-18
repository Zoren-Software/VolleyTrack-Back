<?php

namespace Tests\Feature\Database\Central;

use Tests\TestCase;

class FailedJobsTest extends CentralBase
{
    protected $table = 'failed_jobs';

    public static $fields = [
        'id',
        'uuid',
        'connection',
        'queue',
        'payload',
        'exception',
        'failed_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'failed_jobs_uuid_unique'
    ]; // Define chaves únicas
}
