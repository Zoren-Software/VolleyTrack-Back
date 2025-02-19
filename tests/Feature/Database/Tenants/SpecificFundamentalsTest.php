<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class SpecificFundamentalsTest extends TenantBase
{
    protected $table = 'specific_fundamentals';

    public static $fields = [
        'id',
        'user_id',
        'name',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public static $fieldTypes = [
        'id'         => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
        'user_id'    => ['type' => 'bigint', 'unsigned' => true],
        'name'       => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];
    

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define o campo auto_increment

    public static $foreignKeys = [
        'specific_fundamentals_user_id_foreign',
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única
}
