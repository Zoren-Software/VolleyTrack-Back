<?php

namespace Tests\Feature\Database\Tenants;

class NotificationsTest extends TenantBase
{
    protected $table = 'notifications';

    public static $fieldTypes = [
        'id' => ['type' => 'char', 'length' => 36],
        'type' => ['type' => 'varchar', 'length' => 255],
        'notifiable_type' => ['type' => 'varchar', 'length' => 255],
        'notifiable_id' => ['type' => 'bigint'],
        'data' => ['type' => 'text'],
        'read_at' => ['type' => 'timestamp', 'nullable' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    public static $primaryKey = ['id']; // Define a chave primária

    public static $autoIncrements = []; // Nenhuma coluna auto_increment, pois `id` é um UUID (char(36))

    public static $foreignKeys = []; // Nenhuma chave estrangeira definida

    public static $uniqueKeys = []; // Nenhuma chave única definida

    public static $indexes = [
        'notifications_notifiable_id_index',
        'notifications_notifiable_type_index',
        'notifications_notifiable_type_notifiable_id_index',
    ]; // Índices definidos na tabela
}
