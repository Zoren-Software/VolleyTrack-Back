<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class TeamsTest extends TenantBase
{
    protected $table = 'teams';

    public static $fields = [
        'id',
        'user_id',
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define o campo auto_increment

    public static $foreignKeys = [
        'teams_user_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única
}
