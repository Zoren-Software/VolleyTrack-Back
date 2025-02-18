<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class NotificationsTest extends TenantBase
{
    protected $table = 'notifications';

    public static $fields = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'created_at',
        'updated_at',
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
