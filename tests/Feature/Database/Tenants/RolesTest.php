<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class RolesTest extends TenantBase
{
    protected $table = 'roles';

    public static $fields = [
        'id',
        'name',
        'guard_name',
        'created_at',
        'updated_at',
    ];

    public static $fieldTypes = [
        'id'          => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
        'name'        => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'guard_name'  => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'created_at'  => ['type' => 'timestamp', 'nullable' => true],
        'updated_at'  => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'roles_name_guard_unique',
    ]; // Chave única definida
}
