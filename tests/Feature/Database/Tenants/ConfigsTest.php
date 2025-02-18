<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class ConfigsTest extends TenantBase
{
    protected $table = 'configs';

    public static $fields = [
        'id',
        'user_id',
        'name_tenant',
        'language_id',
        'created_at',
        'updated_at',
        'deleted_at',
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
