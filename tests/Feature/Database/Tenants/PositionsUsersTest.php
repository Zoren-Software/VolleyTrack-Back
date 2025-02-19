<?php

namespace Tests\Feature\Database\Tenants;

class PositionsUsersTest extends TenantBase
{
    protected $table = 'positions_users';

    public static $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'position_id' => ['type' => 'bigint', 'unsigned' => true],
        'user_id' => ['type' => 'bigint', 'unsigned' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'positions_users_user_id_foreign',
        'positions_users_position_id_foreign',
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única definida
}
