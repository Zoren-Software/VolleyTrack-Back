<?php

namespace Tests\Feature\Database\Tenants;

class FundamentalsTrainingsTest extends TenantBase
{
    protected $table = 'fundamentals_trainings';

    public static $fieldTypes = [
        'id' => ['type' => 'bigint'],
        'fundamental_id' => ['type' => 'bigint'],
        'training_id' => ['type' => 'bigint'],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'fundamentals_trainings_fundamental_id_foreign',
        'fundamentals_trainings_training_id_foreign',
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = [];
}
