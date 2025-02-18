<?php

namespace Tests\Feature\Database\Central;

use Tests\TestCase;

class DomainsTest extends CentralBase
{
    protected $table = 'domains';

    public static $fields = [
        'id',
        'domain',
        'tenant_id',
        'created_at',
        'updated_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'domains_tenant_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = [
        'domains_domain_unique'
    ]; // Define as chaves únicas
}
