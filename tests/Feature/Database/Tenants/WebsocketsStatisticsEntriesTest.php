<?php

namespace Tests\Feature\Database\Tenants;

class WebsocketsStatisticsEntriesTest extends TenantBase
{
    protected string $table = 'websockets_statistics_entries';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'int', 'precision' => 10, 'unsigned' => true],
        'app_id' => ['type' => 'varchar', 'length' => 255, 'collation' => 'utf8mb4_unicode_ci'],
        'peak_connection_count' => ['type' => 'int', 'precision' => 11],
        'websocket_message_count' => ['type' => 'int', 'precision' => 11],
        'api_message_count' => ['type' => 'int', 'precision' => 11],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = ['id']; // Define a chave primária

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = ['id']; // Define quais campos são auto_increment

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = []; // Nenhuma chave estrangeira foi definida

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = []; // Nenhuma chave única foi definida
}
