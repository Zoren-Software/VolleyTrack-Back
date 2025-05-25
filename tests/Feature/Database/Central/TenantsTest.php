<?php

namespace Tests\Feature\Database\Central;

class TenantsTest extends CentralBase
{
    protected string $table = 'tenants';

    protected static array $fieldTypes = [
        'id' => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'data' => ['type' => 'longtext', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = []; // Nenhum campo auto_increment definido

    protected static array $foreignKeys = []; // Nenhuma chave estrangeira definida

    protected static array $uniqueKeys = [
        'tenants_id_unique',
    ]; // Define as chaves únicas
}
