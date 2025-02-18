<?php

namespace Tests\Feature\Database\Central;

use Tests\TestCase;

class UsersTest extends CentralBase
{
    protected $table = 'users';

    public static $fields = [
        'id',
        'name',
        'email',
        'github_id',
        'auth_type',
        'email_verified_at',
        'password',
        'remember_token',
        'profile_photo_path',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'users_email_unique'
    ]; // Define as chaves únicas
}
