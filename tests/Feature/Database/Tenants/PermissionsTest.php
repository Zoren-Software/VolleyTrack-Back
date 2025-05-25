<?php

namespace Tests\Feature\Database\Tenants;

class PermissionsTest extends TenantBase
{
    protected string $table = 'permissions';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'name' => ['type' => 'varchar', 'length' => 255],
        'guard_name' => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    protected static array $foreignKeys = []; // Nenhuma chave estrangeira definida

    protected static array $uniqueKeys = [
        'permissions_name_guard_name_unique',
    ]; // Chave única na tabela

    protected static array $indexes = []; // Nenhum outro índice específico definido
}
