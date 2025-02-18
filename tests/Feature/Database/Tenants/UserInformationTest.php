<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class UserInformationTest extends TenantBase
{
    protected $table = 'user_information';

    public static $fields = [
        'id',
        'user_id',
        'cpf',
        'phone',
        'rg',
        'birth_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira foi definida

    public static $uniqueKeys = [
        'user_information_user_id_unique',
        'user_information_cpf_unique',
        'user_information_rg_unique',
    ]; // Define as chaves únicas
}
