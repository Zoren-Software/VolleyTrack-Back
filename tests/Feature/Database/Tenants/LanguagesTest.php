<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class LanguagesTest extends TenantBase
{
    protected $table = 'languages';

    public static $fields = [
        'id',
        'slug',
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = [
        'languages_slug_unique',
        'languages_name_unique',
    ]; // Define as chaves únicas
}
