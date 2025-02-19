<?php

namespace Tests\Feature\Database\Central;

class TenantsTest extends CentralBase
{
    protected $table = 'tenants';

    public static $fieldTypes = [
        'id' => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'data' => ['type' => 'longtext', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = []; // Nenhum campo auto_increment definido

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'tenants_id_unique',
    ]; // Define as chaves únicas
}
