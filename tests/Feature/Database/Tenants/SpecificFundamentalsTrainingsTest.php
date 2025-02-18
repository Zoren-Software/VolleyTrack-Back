<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class SpecificFundamentalsTrainingsTest extends TenantBase
{
    protected $table = 'specific_fundamentals_trainings';

    public static $fields = [
        'id',
        'specific_fundamental_id',
        'training_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define o campo auto_increment

    public static $foreignKeys = [
        'specific_fundamentals_trainings_specific_fundamental_id_foreign',
        'specific_fundamentals_trainings_training_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única
}
