<?php

namespace Tests\Feature\Database\Tenants;

class MigrationsTest extends TenantBase
{
    protected string $table = 'migrations';

    protected static array $fieldTypes = [
        'id' => ['type' => 'int'],
        'migration' => ['type' => 'varchar', 'length' => 255],
        'batch' => ['type' => 'int'],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    protected static array $foreignKeys = []; // Nenhuma chave estrangeira definida

    protected static array $uniqueKeys = []; // Nenhuma chave única definida
}
