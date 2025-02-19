<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class TrainingsTest extends TenantBase
{
    protected $table = 'trainings';

    public static $fieldTypes = [
        'id'           => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
        'team_id'      => ['type' => 'bigint', 'unsigned' => true],
        'user_id'      => ['type' => 'bigint', 'unsigned' => true],
        'name'         => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'description'  => ['type' => 'text', 'collation' => 'utf8mb4_unicode_ci', 'nullable' => true],
        'status'       => ['type' => 'tinyint', 'length' => 1],
        'date_start'   => ['type' => 'datetime'],
        'date_end'     => ['type' => 'datetime'],
        'deleted_at'   => ['type' => 'timestamp', 'nullable' => true],
        'created_at'   => ['type' => 'timestamp', 'nullable' => true],
        'updated_at'   => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define o campo auto_increment

    public static $foreignKeys = [
        'trainings_user_id_foreign',
        'trainings_team_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única
}
