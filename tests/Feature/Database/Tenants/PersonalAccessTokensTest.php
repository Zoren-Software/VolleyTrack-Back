<?php

namespace Tests\Feature\Database\Tenants;

class PersonalAccessTokensTest extends TenantBase
{
    protected $table = 'personal_access_tokens';

    public static $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'tokenable_type' => ['type' => 'varchar', 'length' => 255],
        'tokenable_id' => ['type' => 'bigint', 'unsigned' => true],
        'name' => ['type' => 'varchar', 'length' => 255],
        'token' => ['type' => 'varchar', 'length' => 64],
        'abilities' => ['type' => 'text', 'nullable' => true],
        'last_used_at' => ['type' => 'timestamp', 'nullable' => true],
        'expires_at' => ['type' => 'timestamp', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'personal_access_tokens_token_unique',
    ]; // Chave única na tabela

    public static $indexes = [
        'personal_access_tokens_tokenable_type_tokenable_id_index',
    ]; // Índice composto
}
