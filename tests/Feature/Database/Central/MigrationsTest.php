<?php

namespace Tests\Feature\Database\Central;

class MigrationsTest extends CentralBase
{
    protected $table = 'migrations';

    public static $fieldTypes = [
        'id' => ['type' => 'int', 'length' => 10],
        'migration' => ['type' => 'varchar', 'length' => 255],
        'batch' => ['type' => 'int', 'length' => 11],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = []; // Nenhuma chave única definida
}
