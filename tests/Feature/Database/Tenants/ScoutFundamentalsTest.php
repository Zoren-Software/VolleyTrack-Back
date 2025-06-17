<?php

namespace Tests\Feature\Database\Tenants;

class ScoutFundamentalsTest extends TenantBase
{
    protected string $table = 'scout_fundamentals';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'user_id' => ['type' => 'bigint', 'unsigned' => true],
        'player_id' => ['type' => 'bigint', 'unsigned' => true],
        'training_id' => ['type' => 'bigint', 'unsigned' => true],
        'position_id' => ['type' => 'bigint', 'unsigned' => true],
        'created_at' => ['type' => 'timestamp', 'nullable' => true],
        'updated_at' => ['type' => 'timestamp', 'nullable' => true],
        'deleted_at' => ['type' => 'timestamp', 'nullable' => true],
    ];

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = ['id'];

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = ['id'];

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = [
        'scout_fundamentals_user_id_foreign',
        'scout_fundamentals_player_id_foreign',
        'scout_fundamentals_training_id_foreign',
        'scout_fundamentals_position_id_foreign',
    ];

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = [];
}
