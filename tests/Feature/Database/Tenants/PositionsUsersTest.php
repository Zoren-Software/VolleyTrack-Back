<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class PositionsUsersTest extends TenantBase
{
    protected $table = 'positions_users';

    public static $fields = [
        'id',
        'position_id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'positions_users_user_id_foreign',
        'positions_users_position_id_foreign',
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única definida
}
