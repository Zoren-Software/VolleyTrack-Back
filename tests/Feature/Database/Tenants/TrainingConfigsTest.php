<?php

namespace Tests\Feature\Database\Tenants;

class TrainingConfigsTest extends TenantBase
{
    protected string $table = 'training_configs';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'config_id' => ['type' => 'bigint', 'unsigned' => true],
        'user_id' => ['type' => 'bigint', 'unsigned' => true],
        'days_notification' => ['type' => 'smallint'],
        'notification_team_by_email' => ['type' => 'tinyint'],
        'notification_technician_by_email' => ['type' => 'tinyint'],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id']; // Define a chave primária

    protected static array $autoIncrements = ['id']; // Define o campo auto_increment

    protected static array $foreignKeys = [
        'training_configs_user_id_foreign',
        'training_configs_config_id_foreign',
    ]; // Define as chaves estrangeiras

    protected static array $uniqueKeys = []; // Nenhuma chave única
}
