<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class PasswordResetsTest extends TenantBase
{
    protected $table = 'password_resets';

    public static $fields = [
        'email',
        'token',
        'created_at',
    ];

    public static $primaryKey = []; // Nenhuma chave primária definida

    public static $autoIncrements = []; // Nenhuma coluna auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira

    public static $uniqueKeys = []; // Nenhuma chave única definida

    public static $indexes = [
        'password_resets_email_index',
    ]; // Índice definido na tabela
}
