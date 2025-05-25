<?php

namespace Tests\Feature\Database\Tenants;

class FundamentalsTest extends TenantBase
{
    protected string $table = 'fundamentals';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'user_id' => ['type' => 'bigint'],
        'name' => ['type' => 'varchar', 'length' => 255],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    protected static array $foreignKeys = [
        'fundamentals_user_id_foreign',
    ]; // Define as chaves estrangeiras

    protected static array $uniqueKeys = [];
}
