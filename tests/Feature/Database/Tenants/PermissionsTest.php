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

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'permissions_name_guard_name_unique',
    ]; // Chave única na tabela

    public static $indexes = []; // Nenhum outro índice específico definido
}
