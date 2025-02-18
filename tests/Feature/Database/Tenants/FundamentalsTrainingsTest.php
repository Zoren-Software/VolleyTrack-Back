<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class FundamentalsTrainingsTest extends TenantBase
{
    protected $table = 'fundamentals_trainings';

    public static $fields = [
        'id',
        'fundamental_id',
        'training_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = [
        'fundamentals_trainings_fundamental_id_foreign',
        'fundamentals_trainings_training_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = [];
}
