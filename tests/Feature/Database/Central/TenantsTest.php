<?php

namespace Tests\Feature\Database\Central;

use Tests\TestCase;

class TenantsTest extends CentralBase
{
    protected $table = 'tenants';

    public static $fields = [
        'id',
        'created_at',
        'updated_at',
        'data',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = []; // Nenhum campo auto_increment definido

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'tenants_id_unique'
    ]; // Define as chaves únicas
}
