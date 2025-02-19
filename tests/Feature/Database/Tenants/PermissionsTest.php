<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class PermissionsTest extends TenantBase
{
    protected $table = 'permissions';

    public static $fields = [
        'id',
        'name',
        'guard_name',
        'created_at',
        'updated_at',
    ];

    public static $fieldTypes = [
        'id'          => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
        'name'        => ['type' => 'varchar', 'length' => 255],
        'guard_name'  => ['type' => 'varchar', 'length' => 255],
        'created_at'  => ['type' => 'timestamp'],
        'updated_at'  => ['type' => 'timestamp'],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'permissions_name_guard_name_unique',
    ]; // Chave única na tabela

    public static $indexes = []; // Nenhum outro índice específico definido
}
