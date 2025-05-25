<?php

namespace Tests\Feature\Database\Tenants;

class SpecificFundamentalsTrainingsTest extends TenantBase
{
    protected string $table = 'specific_fundamentals_trainings';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'specific_fundamental_id' => ['type' => 'bigint', 'unsigned' => true],
        'training_id' => ['type' => 'bigint', 'unsigned' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define o campo auto_increment

    protected static array $foreignKeys = [
        'specific_fundamentals_trainings_specific_fundamental_id_foreign',
        'specific_fundamentals_trainings_training_id_foreign',
    ]; // Define as chaves estrangeiras

    protected static array $uniqueKeys = []; // Nenhuma chave única
}
