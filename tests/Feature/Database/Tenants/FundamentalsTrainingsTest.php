<?php

namespace Tests\Feature\Database\Tenants;

class FundamentalsTrainingsTest extends TenantBase
{
    protected string $table = 'fundamentals_trainings';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'fundamental_id' => ['type' => 'bigint'],
        'training_id' => ['type' => 'bigint'],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    protected static array $foreignKeys = [
        'fundamentals_trainings_fundamental_id_foreign',
        'fundamentals_trainings_training_id_foreign',
    ]; // Define as chaves estrangeiras

    protected static array $uniqueKeys = [];
}
