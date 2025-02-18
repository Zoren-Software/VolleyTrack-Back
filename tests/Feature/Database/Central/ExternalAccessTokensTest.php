<?php

namespace Tests\Feature\Database\Central;

use Tests\TestCase;

class ExternalAccessTokensTest extends CentralBase
{
    protected $table = 'external_access_tokens';

    public static $fields = [
        'id',
        'token',
        'created_at',
        'updated_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = []; // Nenhuma chave única definida
}
