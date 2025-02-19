<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class TrainingConfigsTest extends TenantBase
{
    protected $table = 'training_configs';

    public static $fields = [
        'id',
        'config_id',
        'user_id',
        'days_notification',
        'notification_team_by_email',
        'notification_technician_by_email',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $fieldTypes = [
        'id'                                => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
        'config_id'                         => ['type' => 'bigint', 'unsigned' => true],
        'user_id'                           => ['type' => 'bigint', 'unsigned' => true],
        'days_notification'                 => ['type' => 'smallint'],
        'notification_team_by_email'        => ['type' => 'tinyint'],
        'notification_technician_by_email'  => ['type' => 'tinyint'],
        'created_at'                        => ['type' => 'timestamp', 'nullable' => true],
        'updated_at'                        => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at'                        => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define o campo auto_increment

    public static $foreignKeys = [
        'training_configs_user_id_foreign',
        'training_configs_config_id_foreign'
    ]; // Define as chaves estrangeiras

    public static $uniqueKeys = []; // Nenhuma chave única
}
