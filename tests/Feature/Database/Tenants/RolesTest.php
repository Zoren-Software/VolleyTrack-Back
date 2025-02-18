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

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'roles_name_guard_unique',
    ]; // Chave única definida
}
