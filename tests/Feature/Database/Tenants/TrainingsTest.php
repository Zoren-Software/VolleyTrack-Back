<?php

namespace Tests\Feature\Database\Tenants;

class TrainingsTest extends TenantBase
{
    /**
     * @var string
     */
    protected string $table = 'trainings';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'team_id' => ['type' => 'bigint', 'unsigned' => true],
        'user_id' => ['type' => 'bigint', 'unsigned' => true],
        'name' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'description' => ['type' => 'text', 'collation' => 'utf8mb4_unicode_ci', 'nullable' => true],
        'status' => ['type' => 'tinyint', 'length' => 1],
        'date_start' => ['type' => 'datetime'],
        'date_end' => ['type' => 'datetime'],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = ['id']; // Define a chave primária

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = ['id']; // Define o campo auto_increment

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = [
        'trainings_user_id_foreign',
        'trainings_team_id_foreign',
    ]; // Define as chaves estrangeiras

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = []; // Nenhuma chave única
}