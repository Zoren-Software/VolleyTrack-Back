<?php

namespace Tests\Feature\Database\Central;

class MigrationsTest extends CentralBase
{
    protected string $table = 'migrations';

    protected static array $fieldTypes = [
        'id' => ['type' => 'int', 'length' => 10],
        'migration' => ['type' => 'varchar', 'length' => 255],
        'batch' => ['type' => 'int', 'length' => 11],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    protected static array $foreignKeys = []; // Nenhuma chave estrangeira definida

    protected static array $uniqueKeys = []; // Nenhuma chave única definida
}
