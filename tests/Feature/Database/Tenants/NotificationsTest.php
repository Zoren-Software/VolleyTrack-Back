<?php

namespace Tests\Feature\Database\Tenants;

class NotificationsTest extends TenantBase
{
    /**
     * @var string
     */
    protected string $table = 'notifications';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'char', 'length' => 36],
        'type' => ['type' => 'varchar', 'length' => 255],
        'notifiable_type' => ['type' => 'varchar', 'length' => 255],
        'notifiable_id' => ['type' => 'bigint'],
        'data' => ['type' => 'text'],
        'read_at' => ['type' => 'timestamp', 'nullable' => true],
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
    protected static array $autoIncrements = []; // Nenhuma coluna auto_increment, pois `id` é um UUID (char(36))

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = []; // Nenhuma chave estrangeira definida

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = []; // Nenhuma chave única definida

    /**
     * @var array<int, string>
     */
    protected static array $indexes = [
        'notifications_notifiable_id_index',
        'notifications_notifiable_type_index',
        'notifications_notifiable_type_notifiable_id_index',
    ]; // Índices definidos na tabela
}
