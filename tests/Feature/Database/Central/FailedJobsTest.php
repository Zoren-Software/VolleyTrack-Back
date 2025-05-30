<?php

namespace Tests\Feature\Database\Central;

class FailedJobsTest extends CentralBase
{
    /**
     * @var string
     */
    protected string $table = 'failed_jobs';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'uuid' => ['type' => 'varchar', 'length' => 255],
        'connection' => ['type' => 'text'],
        'queue' => ['type' => 'text'],
        'payload' => ['type' => 'longtext'],
        'exception' => ['type' => 'longtext'],
        'failed_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = ['id']; // Define a chave primária

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = []; // Nenhuma chave estrangeira definida

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = [
        'failed_jobs_uuid_unique',
    ];
}
