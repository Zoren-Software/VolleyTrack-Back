<?php

namespace Tests\Feature\Database\Tenants;

class PasswordResetsTest extends TenantBase
{
    protected string $table = 'password_resets';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'email' => ['type' => 'varchar', 'length' => 255],
        'token' => ['type' => 'varchar', 'length' => 255],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = []; // Nenhuma chave primária definida

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = []; // Nenhuma coluna auto_increment

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = []; // Nenhuma chave estrangeira

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = []; // Nenhuma chave única definida

    /**
     * @var array<int, string>
     */
    protected static array $indexes = [
        'password_resets_email_index',
    ]; // Índice definido na tabela
}
