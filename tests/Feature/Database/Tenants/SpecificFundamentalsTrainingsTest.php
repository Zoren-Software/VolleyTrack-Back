<?php

namespace Tests\Feature\Database\Tenants;

class SpecificFundamentalsTrainingsTest extends TenantBase
{
    protected $table = 'specific_fundamentals_trainings';

    public static $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'specific_fundamental_id' => ['type' => 'bigint', 'unsigned' => true],
        'training_id' => ['type' => 'bigint', 'unsigned' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define o campo auto_increment

    public static $foreignKeys = [
        'specific_fundamentals_trainings_specific_fundamental_id_foreign',
        'specific_fundamentals_trainings_training_id_foreign',
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única
}
