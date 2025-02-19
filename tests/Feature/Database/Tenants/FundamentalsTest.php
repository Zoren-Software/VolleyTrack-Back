<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class FundamentalsTest extends TenantBase
{
    protected $table = 'fundamentals';

    public static $fields = [
        'id',
        'user_id',
        'name',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public static $fieldTypes = [
        'id'         => ['type' => 'bigint'],
        'user_id'    => ['type' => 'bigint'],
        'name'       => ['type' => 'varchar', 'length' => 255],
        'deleted_at' => ['type' => 'timestamp'],
        'created_at' => ['type' => 'timestamp'],
        'updated_at' => ['type' => 'timestamp'],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'fundamentals_user_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = [];
}
