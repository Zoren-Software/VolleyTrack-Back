<?php

namespace Tests\Feature\Database\Tenants;

class ModelHasPermissionsTest extends TenantBase
{
    protected string $table = 'model_has_permissions';

    protected static array $fieldTypes = [
        'permission_id' => ['type' => 'bigint'],
        'model_type' => ['type' => 'varchar', 'length' => 255],
        'model_id' => ['type' => 'bigint'],
    ];

    protected static array $primaryKey = ['permission_id', 'model_type', 'model_id']; // Define a chave primária composta

    protected static array $autoIncrements = []; // Nenhuma coluna auto_increment

    protected static array $foreignKeys = [
        'model_has_permissions_permission_id_foreign', // Verifique o nome correto no banco
    ];

    protected static array $uniqueKeys = []; // Nenhuma chave única definida

    protected static array $indexes = [
        'model_has_permissions_model_id_model_type_index',
    ]; // Índices definidos na tabela
}
