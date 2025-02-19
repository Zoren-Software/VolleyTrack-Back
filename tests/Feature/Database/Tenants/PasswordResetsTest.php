<?php

namespace Tests\Feature\Database\Tenants;

class PasswordResetsTest extends TenantBase
{
    protected $table = 'password_resets';

    public static $fieldTypes = [
        'email' => ['type' => 'varchar', 'length' => 255],
        'token' => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = []; // Nenhuma chave primária definida

    public static $autoIncrements = []; // Nenhuma coluna auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira

    public static $uniqueKeys = []; // Nenhuma chave única definida

    public static $indexes = [
        'password_resets_email_index',
    ]; // Índice definido na tabela
}
