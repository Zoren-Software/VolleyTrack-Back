<?php

namespace Tests\Feature\Database\Central;

class FailedJobsTest extends CentralBase
{
    protected string $table = 'failed_jobs';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'uuid' => ['type' => 'varchar', 'length' => 255],
        'connection' => ['type' => 'text'],
        'queue' => ['type' => 'text'],
        'payload' => ['type' => 'longtext'],
        'exception' => ['type' => 'longtext'],
        'failed_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    protected static array $foreignKeys = []; // Nenhuma chave estrangeira definida

    protected static array $uniqueKeys = [
        'failed_jobs_uuid_unique',
    ]; // Define chaves únicas
}
