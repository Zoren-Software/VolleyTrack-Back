<?php

namespace Tests\Feature\Database\Tenants;

class ScoutsBlockTest extends TenantBase
{
    protected string $table = 'scouts_block';

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [
        'id' => ['type' => 'bigint', 'unsigned' => true],
        'user_id' => ['type' => 'bigint', 'unsigned' => true],
        'scout_fundamental_id' => ['type' => 'bigint', 'unsigned' => true],
        'total_a' => ['type' => 'int'],
        'total_b' => ['type' => 'int'],
        'total_c' => ['type' => 'int'],
        'total' => ['type' => 'int'],
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
        'scouts_block_user_id_foreign',
        'scouts_block_scout_fundamental_id_foreign',
    ];

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = [];
}
