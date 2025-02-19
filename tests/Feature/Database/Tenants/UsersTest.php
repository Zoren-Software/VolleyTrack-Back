<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class UsersTest extends TenantBase
{
    protected $table = 'users';

    public static $fieldTypes = [
        'id'                   => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
        'user_id'              => ['type' => 'bigint', 'unsigned' => true, 'nullable' => true],
        'name'                 => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'email'                => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'email_verified_at'    => ['type' => 'timestamp', 'nullable' => true],
        'password'             => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci', 'nullable' => true],
        'remember_token'       => ['type' => 'varchar', 'length' => 100, 'collation' => 'utf8mb4_unicode_ci', 'nullable' => true],
        'set_password_token'   => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci', 'nullable' => true],
        'created_at'           => ['type' => 'timestamp', 'nullable' => true],
        'updated_at'           => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at'           => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'users_user_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = [];
}
