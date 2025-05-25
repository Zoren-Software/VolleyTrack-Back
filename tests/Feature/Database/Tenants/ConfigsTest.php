<?php

namespace Tests\Feature\Database\Tenants;

class ConfigsTest extends TenantBase
{
    protected string $table = 'configs';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'user_id' => ['type' => 'bigint'],
        'name_tenant' => ['type' => 'varchar', 'length' => 50],
        'language_id' => ['type' => 'bigint'],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    protected static array $foreignKeys = [
        'configs_user_id_foreign',
        'configs_language_id_foreign',
    ]; // Define as chaves estrangeiras

    protected static array $uniqueKeys = [
        'configs_name_tenant_unique',
    ];
}
