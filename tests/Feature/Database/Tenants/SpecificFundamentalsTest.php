<?php

namespace Tests\Feature\Database\Tenants;

class SpecificFundamentalsTest extends TenantBase
{
    protected string $table = 'specific_fundamentals';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'user_id' => ['type' => 'bigint', 'unsigned' => true],
        'name' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define o campo auto_increment

    protected static array $foreignKeys = [
        'specific_fundamentals_user_id_foreign',
    ]; // Define as chaves estrangeiras

    protected static array $uniqueKeys = []; // Nenhuma chave única
}
