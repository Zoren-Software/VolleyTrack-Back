<?php

namespace Tests\Feature\Database\Tenants;

class MigrationsTest extends TenantBase
{
    protected $table = 'migrations';

    public static $fieldTypes = [
        'id' => ['type' => 'int'],
        'migration' => ['type' => 'varchar', 'length' => 255],
        'batch' => ['type' => 'int'],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = []; // Nenhuma chave única definida
}
