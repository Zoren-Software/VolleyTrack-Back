<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class PersonalAccessTokensTest extends TenantBase
{
    protected $table = 'personal_access_tokens';

    public static $fields = [
        'id',
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
        'created_at',
        'updated_at',
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
