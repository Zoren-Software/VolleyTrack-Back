<?php

namespace Tests\Feature\Database\Central;

class MigrationsTest extends CentralBase
{
    protected string $table = 'migrations';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'int', 'length' => 10],
        'migration' => ['type' => 'varchar', 'length' => 255],
        'batch' => ['type' => 'int', 'length' => 11],
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
