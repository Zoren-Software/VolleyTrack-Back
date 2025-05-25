<?php

namespace Tests\Feature\Database\Tenants;

class ModelHasRolesTest extends TenantBase
{
    protected string $table = 'model_has_roles';

    protected static array $fieldTypes = [
        'role_id' => ['type' => 'bigint'],
        'model_type' => ['type' => 'varchar', 'length' => 255],
        'model_id' => ['type' => 'bigint'],
    ];

    protected static array $primaryKey = ['role_id', 'model_type', 'model_id']; // Define a chave primária composta

    protected static array $autoIncrements = []; // Nenhuma coluna auto_increment

    protected static array $foreignKeys = [
        'model_has_roles_role_id_foreign', // Nome correto da FK no banco
    ];

    protected static array $uniqueKeys = []; // Nenhuma chave única definida

    protected static array $indexes = [
        'model_has_roles_model_id_model_type_index',
    ]; // Índices definidos na tabela
}
