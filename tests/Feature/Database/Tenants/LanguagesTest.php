<?php

namespace Tests\Feature\Database\Tenants;

class LanguagesTest extends TenantBase
{
    protected string $table = 'languages';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'slug' => ['type' => 'varchar', 'length' => 7],
        'name' => ['type' => 'varchar', 'length' => 20],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = ['id']; // Define a chave primária

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = []; // Nenhuma chave estrangeira definida

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = [
        'languages_slug_unique',
        'languages_name_unique',
    ];
}
