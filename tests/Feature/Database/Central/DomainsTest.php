<?php

namespace Tests\Feature\Database\Central;

class DomainsTest extends CentralBase
{
    protected $table = 'domains';

    public static $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'domain' => ['type' => 'varchar', 'length' => 255],
        'tenant_id' => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'domains_tenant_id_foreign',
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = [
        'domains_domain_unique',
    ]; // Define as chaves únicas
}
