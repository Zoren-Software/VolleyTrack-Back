<?php

namespace Tests\Feature\Database\Tenants;

class PositionsTest extends TenantBase
{
    /**
     * @var string
     */
    protected string $table = 'positions';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'user_id' => ['type' => 'bigint', 'unsigned' => true],
        'name' => ['type' => 'varchar', 'length' => 255],
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
        'positions_user_id_foreign',
    ]; // Define as chaves estrangeiras

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = []; // Nenhuma chave única definida
}
