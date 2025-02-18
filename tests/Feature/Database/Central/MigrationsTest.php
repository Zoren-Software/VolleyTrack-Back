<?php

namespace Tests\Feature\Database\Central;

use Tests\TestCase;

class MigrationsTest extends CentralBase
{
    protected $table = 'migrations';

    public static $fields = [
        'id',
        'migration',
        'batch',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = []; // Nenhuma chave única definida
}
