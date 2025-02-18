<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class PositionsTest extends TenantBase
{
    protected $table = 'positions';

    public static $fields = [
        'id',
        'user_id',
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'positions_user_id_foreign',
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única definida
}
