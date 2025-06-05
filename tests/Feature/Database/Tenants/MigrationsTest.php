<?php

namespace Tests\Feature\Database\Tenants;

class MigrationsTest extends TenantBase
{
    protected string $table = 'migrations';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'int'],
        'migration' => ['type' => 'varchar', 'length' => 255],
        'batch' => ['type' => 'int'],
    ];

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = ['id']; // Define a chave primária

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = []; // Nenhuma chave estrangeira definida

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = []; // Nenhuma chave única definida
}
