<?php

namespace Tests\Feature\Database\Tenants;

class ModelHasRolesTest extends TenantBase
{
    protected $table = 'model_has_roles';

    public static $fieldTypes = [
        'role_id' => ['type' => 'bigint'],
        'model_type' => ['type' => 'varchar', 'length' => 255],
        'model_id' => ['type' => 'bigint'],
    ];

    public static $primaryKey = ['role_id', 'model_type', 'model_id']; // Define a chave primária composta

    public static $autoIncrements = []; // Nenhuma coluna auto_increment

    public static $foreignKeys = [
        'model_has_roles_role_id_foreign', // Nome correto da FK no banco
    ];

    public static $uniqueKeys = []; // Nenhuma chave única definida

    public static $indexes = [
        'model_has_roles_model_id_model_type_index',
    ]; // Índices definidos na tabela
}
