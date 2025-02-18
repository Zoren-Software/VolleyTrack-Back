<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class ModelHasRolesTest extends TenantBase
{
    protected $table = 'model_has_roles';

    public static $fields = [
        'role_id',
        'model_type',
        'model_id',
    ];

    public static $primaryKey = ['role_id', 'model_type', 'model_id']; // Define a chave primária composta

    public static $autoIncrements = []; // Nenhuma coluna auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = []; // Nenhuma chave única definida

    public static $indexes = [
        'model_has_roles_model_id_model_type_index'
    ]; // Índices definidos na tabela
}
