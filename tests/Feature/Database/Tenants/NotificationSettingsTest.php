<?php

namespace Tests\Feature\Database\Tenants;

class NotificationSettingsTest extends TenantBase
{
    protected string $table = 'notification_settings';

    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'user_id' => ['type' => 'bigint', 'unsigned' => true],
        'notification_type_id' => ['type' => 'bigint', 'unsigned' => true],
        'via_email' => ['type' => 'tinyint'], // boolean
        'via_system' => ['type' => 'tinyint'], // boolean
        'is_active' => ['type' => 'tinyint'], // boolean
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    protected static array $primaryKey = ['id'];

    protected static array $autoIncrements = ['id'];

    protected static array $foreignKeys = [
        'notification_settings_user_id_foreign',
        'notification_settings_notification_type_id_foreign',
    ];

    protected static array $uniqueKeys = [
        'notification_settings_user_type_unique',
    ];
}
