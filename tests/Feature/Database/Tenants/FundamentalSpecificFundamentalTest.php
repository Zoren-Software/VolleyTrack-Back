<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class FundamentalSpecificFundamentalTest extends TenantBase
{
    protected $table = 'fundamental_specific_fundamental';

    public static $fields = [
        'id',
        'fundamental_id',
        'specific_fundamental_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'fundamental_specific_fundamental_fundamental_id_foreign',
        'fundamental_specific_fundamental_specific_fundamental_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = [];
}
