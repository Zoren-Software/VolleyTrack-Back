<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class WebsocketsStatisticsEntriesTest extends TenantBase
{
    protected $table = 'websockets_statistics_entries';

    public static $fields = [
        'id',
        'app_id',
        'peak_connection_count',
        'websocket_message_count',
        'api_message_count',
        'created_at',
        'updated_at',
    ];

    public static $fieldTypes = [
        'id'                      => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
        'app_id'                  => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'peak_connection_count'    => ['type' => 'int', 'length' => 11],
        'websocket_message_count'  => ['type' => 'int', 'length' => 11],
        'api_message_count'        => ['type' => 'int', 'length' => 11],
        'created_at'               => ['type' => 'timestamp', 'nullable' => true],
        'updated_at'               => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira foi definida

    public static $uniqueKeys = []; // Nenhuma chave única foi definida
}
