<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class LanguagesTest extends TenantBase
{
    protected $table = 'languages';

    public static $fieldTypes = [
        'id'         => ['type' => 'bigint'],
        'slug'       => ['type' => 'varchar', 'length' => 7],
        'name'       => ['type' => 'varchar', 'length' => 20],
        'created_at' => ['type' => 'timestamp'],
        'updated_at' => ['type' => 'timestamp'],
        'deleted_at' => ['type' => 'timestamp'],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'languages_slug_unique',
        'languages_name_unique',
    ]; // Define as chaves únicas
}
