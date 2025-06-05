<?php

namespace Tests\Feature\Database\Tenants;

class RoleHasPermissionsTest extends TenantBase
{
    protected string $table = 'role_has_permissions';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'permission_id' => ['type' => 'bigint', 'unsigned' => true],
        'role_id' => ['type' => 'bigint', 'unsigned' => true],
    ];

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = [
        'permission_id',
        'role_id',
    ]; // Define a chave primária composta

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = []; // Nenhum campo auto_increment

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = [
        'role_has_permissions_role_id_foreign',
        'role_has_permissions_permission_id_foreign',
    ];

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = []; // Nenhuma chave única
}
