<?php

namespace Tests\Feature\Database\Tenants;

class FundamentalSpecificFundamentalTest extends TenantBase
{
    protected string $table = 'fundamental_specific_fundamental';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'fundamental_id' => ['type' => 'bigint'],
        'specific_fundamental_id' => ['type' => 'bigint'],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
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
    protected static array $foreignKeys = [
        'fundamental_specific_fundamental_fundamental_id_foreign',
        'fundamental_specific_fundamental_specific_fundamental_id_foreign',
    ]; // Define as chaves estrangeiras

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = [];
}
