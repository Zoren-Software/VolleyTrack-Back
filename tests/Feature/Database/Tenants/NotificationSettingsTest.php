<?php

namespace Tests\Feature\Database\Tenants;

class NotificationSettingsTest extends TenantBase
{
    protected $table = 'notification_settings';

    public static $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'user_id' => ['type' => 'bigint', 'unsigned' => true],
        'notification_type_id' => ['type' => 'bigint', 'unsigned' => true],
        'via_email' => ['type' => 'tinyint'], // boolean
        'via_system' => ['type' => 'tinyint'], // boolean
        'is_active' => ['type' => 'tinyint'], // boolean
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id'];

    public static $autoIncrements = ['id'];

    public static $foreignKeys = [
        'notification_settings_user_id_foreign',
        'notification_settings_notification_type_id_foreign',
    ];

    public static $uniqueKeys = [
        'notification_settings_user_type_unique',
    ];
}
