<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class ModelHasPermissionsTest extends TenantBase
{
    protected $table = 'model_has_permissions';

    public static $fields = [
        'permission_id',
        'model_type',
        'model_id',
    ];

    public static $fieldTypes = [
        'permission_id' => ['type' => 'bigint'],
        'model_type'    => ['type' => 'varchar', 'length' => 255],
        'model_id'      => ['type' => 'bigint'],
    ];

    public static $primaryKey = ['permission_id', 'model_type', 'model_id']; // Define a chave primária composta

    public static $autoIncrements = []; // Nenhuma coluna auto_increment

    public static $foreignKeys = [
        'model_has_permissions_permission_id_foreign' // Verifique o nome correto no banco
    ];

    public static $uniqueKeys = []; // Nenhuma chave única definida

    public static $indexes = [
        'model_has_permissions_model_id_model_type_index'
    ]; // Índices definidos na tabela
}
