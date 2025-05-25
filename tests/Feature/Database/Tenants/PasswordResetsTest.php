<?php

namespace Tests\Feature\Database\Tenants;

class PasswordResetsTest extends TenantBase
{
    protected string $table = 'password_resets';

    protected static array $fieldTypes = [
        'email' => ['type' => 'varchar', 'length' => 255],
        'token' => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = []; // Nenhuma chave primária definida

    protected static array $autoIncrements = []; // Nenhuma coluna auto_increment

    protected static array $foreignKeys = []; // Nenhuma chave estrangeira

    protected static array $uniqueKeys = []; // Nenhuma chave única definida

    protected static array $indexes = [
        'password_resets_email_index',
    ]; // Índice definido na tabela
}
