<?php

namespace Tests\Feature\Database\Tenants;

class FundamentalSpecificFundamentalTest extends TenantBase
{
    protected string $table = 'fundamental_specific_fundamental';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'fundamental_id' => ['type' => 'bigint'],
        'specific_fundamental_id' => ['type' => 'bigint'],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    protected static array $foreignKeys = [
        'fundamental_specific_fundamental_fundamental_id_foreign',
        'fundamental_specific_fundamental_specific_fundamental_id_foreign',
    ]; // Define as chaves estrangeiras

    protected static array $uniqueKeys = [];
}
