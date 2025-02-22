<?php

namespace Tests\Feature\Database\Tenants;

class RoleHasPermissionsTest extends TenantBase
{
    protected $table = 'role_has_permissions';

    public static $fieldTypes = [
        'permission_id' => ['type' => 'bigint', 'unsigned' => true],
        'role_id' => ['type' => 'bigint', 'unsigned' => true],
    ];

    public static $primaryKey = [
        'permission_id',
        'role_id',
    ]; // Define a chave primária composta

    public static $autoIncrements = []; // Nenhum campo auto_increment

    public static $foreignKeys = [
        'role_has_permissions_role_id_foreign',
        'role_has_permissions_permission_id_foreign',
    ];

    public static $uniqueKeys = []; // Nenhuma chave única
}
