<?php

namespace Tests\Feature\Database\Central;

class DomainsTest extends CentralBase
{
    protected string $table = 'domains';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'domain' => ['type' => 'varchar', 'length' => 255],
        'tenant_id' => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id'];

    protected static array $autoIncrements = ['id'];

    protected static array $foreignKeys = [
        'domains_tenant_id_foreign',
    ];

    protected static array $uniqueKeys = [
        'domains_domain_unique',
    ];
}
