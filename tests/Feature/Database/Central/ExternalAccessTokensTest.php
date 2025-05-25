<?php

namespace Tests\Feature\Database\Central;

class ExternalAccessTokensTest extends CentralBase
{
    protected string $table = 'external_access_tokens';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'token' => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    protected static array $foreignKeys = []; // Nenhuma chave estrangeira definida

    protected static array $uniqueKeys = []; // Nenhuma chave única definida
}
