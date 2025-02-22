<?php

namespace Tests\Feature\Database\Central;

class FailedJobsTest extends CentralBase
{
    protected $table = 'failed_jobs';

    public static $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'uuid' => ['type' => 'varchar', 'length' => 255],
        'connection' => ['type' => 'text'],
        'queue' => ['type' => 'text'],
        'payload' => ['type' => 'longtext'],
        'exception' => ['type' => 'longtext'],
        'failed_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'failed_jobs_uuid_unique',
    ]; // Define chaves únicas
}
