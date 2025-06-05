<?php

namespace Tests\Feature\Database\Central;

class TenantsTest extends CentralBase
{
    protected string $table = 'tenants';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'data' => ['type' => 'longtext', 'nullable' => true],
    ];

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = ['id']; // Define a chave primária

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = []; // Nenhum campo auto_increment definido

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = []; // Nenhuma chave estrangeira definida

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = [
        'tenants_id_unique',
    ];
}
