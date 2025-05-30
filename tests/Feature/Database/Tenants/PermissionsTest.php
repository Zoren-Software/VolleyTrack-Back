<?php

namespace Tests\Feature\Database\Tenants;

class PermissionsTest extends TenantBase
{
    /**
     * @var string
     */
    protected string $table = 'permissions';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'name' => ['type' => 'varchar', 'length' => 255],
        'guard_name' => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
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
        'permissions_name_guard_name_unique',
    ]; // Chave única na tabela

    /**
     * @var array<int, string>
     */
    protected static array $indexes = []; // Nenhum outro índice específico definido
}
