<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class RoleHasPermissionsTest extends TenantBase
{
    protected $table = 'role_has_permissions';

    public static $fields = [
        'permission_id',
        'role_id',
    ];

    public static $primaryKey = [
        'permission_id',
        'role_id',
    ]; // Define a chave primária composta

    public static $autoIncrements = []; // Nenhum campo auto_increment

    public static $foreignKeys = [
        'role_has_permissions_role_id_foreign',
    ]; // Chaves estrangeiras definidas

    public static $uniqueKeys = []; // Nenhuma chave única
}
