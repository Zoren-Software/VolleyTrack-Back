<?php

namespace Tests\Feature\Database\Central;

use Tests\TestCase;

class ExternalAccessTokensTest extends CentralBase
{
    protected $table = 'external_access_tokens';

    public static $fieldTypes = [
        'id'         => ['type' => 'bigint', 'auto_increment' => true],
        'token'      => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];
    

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = []; // Nenhuma chave única definida
}
