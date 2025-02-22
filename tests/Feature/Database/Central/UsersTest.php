<?php

namespace Tests\Feature\Database\Central;

class UsersTest extends CentralBase
{
    protected $table = 'users';

    public static $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'name' => ['type' => 'varchar', 'length' => 255],
        'email' => ['type' => 'varchar', 'length' => 255],
        'github_id' => ['type' => 'varchar', 'length' => 255, 'nullable' => true],
        'auth_type' => ['type' => 'varchar', 'length' => 255, 'nullable' => true],
        'email_verified_at' => ['type' => 'timestamp', 'nullable' => true],
        'password' => ['type' => 'varchar', 'length' => 255],
        'remember_token' => ['type' => 'varchar', 'length' => 100, 'nullable' => true],
        'profile_photo_path' => ['type' => 'text', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'users_email_unique',
    ]; // Define as chaves únicas
}
