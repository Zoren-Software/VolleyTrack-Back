<?php

namespace Tests\Feature\Database\Tenants;

class WebsocketsStatisticsEntriesTest extends TenantBase
{
    protected $table = 'websockets_statistics_entries';

    public static $fieldTypes = [
        'id' => ['type' => 'int', 'precision' => 10, 'unsigned' => true],
        'app_id' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'peak_connection_count' => ['type' => 'int', 'precision' => 11],
        'websocket_message_count' => ['type' => 'int', 'precision' => 11],
        'api_message_count' => ['type' => 'int', 'precision' => 11],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira foi definida

    public static $uniqueKeys = []; // Nenhuma chave única foi definida
}
