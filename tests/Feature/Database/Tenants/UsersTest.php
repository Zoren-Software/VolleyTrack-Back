<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class UsersTest extends TenantBase
{
    protected $table = 'users';

    public static $fields = [
        'id',
        'user_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'set_password_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'users_user_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = [];
}
