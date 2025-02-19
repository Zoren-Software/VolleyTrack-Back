<?php

namespace Tests\Feature\Database\Tenants;

class FundamentalSpecificFundamentalTest extends TenantBase
{
    protected $table = 'fundamental_specific_fundamental';

    public static $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'fundamental_id' => ['type' => 'bigint'],
        'specific_fundamental_id' => ['type' => 'bigint'],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'fundamental_specific_fundamental_fundamental_id_foreign',
        'fundamental_specific_fundamental_specific_fundamental_id_foreign',
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = [];
}
