<?php

namespace Tests\Feature\Database\Tenants;

class LanguagesTest extends TenantBase
{
    protected string $table = 'languages';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'slug' => ['type' => 'varchar', 'length' => 7],
        'name' => ['type' => 'varchar', 'length' => 20],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    protected static array $foreignKeys = []; // Nenhuma chave estrangeira definida

    protected static array $uniqueKeys = [
        'languages_slug_unique',
        'languages_name_unique',
    ]; // Define as chaves únicas
}
