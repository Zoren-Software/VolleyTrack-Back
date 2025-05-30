<?php

namespace Tests\Feature\Database\Tenants;

class ConfigsTest extends TenantBase
{
    /**
     * @var string
     */
    protected string $table = 'configs';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'user_id' => ['type' => 'bigint'],
        'name_tenant' => ['type' => 'varchar', 'length' => 50],
        'language_id' => ['type' => 'bigint'],
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
    protected static array $foreignKeys = [
        'configs_user_id_foreign',
        'configs_language_id_foreign',
    ];

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = [
        'configs_name_tenant_unique',
    ];
}
