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

    public static $fieldTypes = [
        'id'          => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
        'user_id'     => ['type' => 'bigint', 'unsigned' => true],
        'cpf'         => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci', 'nullable' => true],
        'phone'       => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci', 'nullable' => true],
        'rg'          => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci', 'nullable' => true],
        'birth_date'  => ['type' => 'date', 'nullable' => true],
        'created_at'  => ['type' => 'timestamp', 'nullable' => true],
        'updated_at'  => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at'  => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'user_information_user_id_foreign' // Nome correto da FK
    ];

    public static $uniqueKeys = [
        'user_information_user_id_unique',
        'user_information_cpf_unique',
        'user_information_rg_unique',
    ]; // Define as chaves únicas
}
