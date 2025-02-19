<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class ConfigsTest extends TenantBase
{
    protected $table = 'configs';

    public static $fieldTypes = [
        'id'           => ['type' => 'bigint'],
        'user_id'      => ['type' => 'bigint'],
        'name_tenant'  => ['type' => 'varchar', 'length' => 50],
        'language_id'  => ['type' => 'bigint'],
        'created_at'   => ['type' => 'timestamp'],
        'updated_at'   => ['type' => 'timestamp'],
        'deleted_at'   => ['type' => 'timestamp'],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'configs_user_id_foreign',
        'configs_language_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = [
        'configs_name_tenant_unique'
    ];
}
