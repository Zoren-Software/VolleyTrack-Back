<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class PositionsTest extends TenantBase
{
    protected $table = 'positions';

    public static $fieldTypes = [
        'id'         => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
        'user_id'    => ['type' => 'bigint', 'unsigned' => true],
        'name'       => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'positions_user_id_foreign',
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única definida
}
