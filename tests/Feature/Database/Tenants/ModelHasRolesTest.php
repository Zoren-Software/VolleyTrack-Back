<?php

namespace Tests\Feature\Database\Tenants;

class ModelHasRolesTest extends TenantBase
{
    /**
     * @var string
     */
    protected string $table = 'model_has_roles';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'role_id' => ['type' => 'bigint'],
        'model_type' => ['type' => 'varchar', 'length' => 255],
        'model_id' => ['type' => 'bigint'],
    ];

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = ['role_id', 'model_type', 'model_id']; // Define a chave primária composta

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = []; // Nenhuma coluna auto_increment

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = [
        'model_has_roles_role_id_foreign', // Nome correto da FK no banco
    ];

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = []; // Nenhuma chave única definida

    /**
     * @var array<int, string>
     */
    protected static array $indexes = [
        'model_has_roles_model_id_model_type_index',
    ]; // Índices definidos na tabela
}
