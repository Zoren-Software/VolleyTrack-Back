<?php

namespace Tests\Feature\Database\Tenants;

class SpecificFundamentalsTrainingsTest extends TenantBase
{
    /**
     * @var string
     */
    protected string $table = 'specific_fundamentals_trainings';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'specific_fundamental_id' => ['type' => 'bigint', 'unsigned' => true],
        'training_id' => ['type' => 'bigint', 'unsigned' => true],
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
        'specific_fundamentals_trainings_specific_fundamental_id_foreign',
        'specific_fundamentals_trainings_training_id_foreign',
    ]; // Define as chaves estrangeiras

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = []; // Nenhuma chave única
}
