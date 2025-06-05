<?php

namespace Tests\Feature\Database\Tenants;

class FundamentalsTrainingsTest extends TenantBase
{
    protected string $table = 'fundamentals_trainings';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'fundamental_id' => ['type' => 'bigint'],
        'training_id' => ['type' => 'bigint'],
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
    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = [
        'fundamentals_trainings_fundamental_id_foreign',
        'fundamentals_trainings_training_id_foreign',
    ]; // Define as chaves estrangeiras

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = [];
}
