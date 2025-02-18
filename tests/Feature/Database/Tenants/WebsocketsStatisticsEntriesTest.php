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

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = ['id']; // Define quais campos são auto_increment

    public static $foreignKeys = []; // Nenhuma chave estrangeira foi definida

    public static $uniqueKeys = []; // Nenhuma chave única foi definida
}
