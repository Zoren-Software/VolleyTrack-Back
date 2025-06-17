<?php

use Illuminate\Support\Facades\DB;

function appHost(): string
{
    $host = config('app.host');

    if (!is_string($host)) {
        throw new \RuntimeException('Configurações de URL inválidas');
    }

    return $host;
}

function appVersion(): string
{
    $path = base_path('composer.json');

    if (!file_exists($path)) {
        return '';
    }

    $composerJson = file_get_contents($path);

    if (!is_string($composerJson)) {
        return '';
    }

    $data = json_decode($composerJson, true);

    if (!is_array($data)) {
        return '';
    }

    return isset($data['version']) && is_string($data['version'])
        ? trim($data['version'])
        : '';
}

function hasForeignKeyExist(string $table, string $nameForeignKey): bool
{
    $databaseName = DB::getDatabaseName();
    $result = DB::select('
        SELECT CONSTRAINT_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
            AND CONSTRAINT_NAME = ?
    ', [$databaseName, $table, $nameForeignKey]);

    return !empty($result);
}

function hasIndexExist(string $table, string $nameIndex): bool
{
    $databaseName = DB::getDatabaseName();
    $result = DB::select('
        SELECT INDEX_NAME
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
            AND INDEX_NAME = ?
    ', [$databaseName, $table, $nameIndex]);

    return !empty($result);
}

function hasEventExist(string $eventName): bool
{
    $dbName = DB::connection()->getDatabaseName();
    $result = DB::select('
        SELECT EVENT_NAME
        FROM INFORMATION_SCHEMA.EVENTS
        WHERE EVENT_SCHEMA = ?
            AND EVENT_NAME = ?
    ', [$dbName, $eventName]);

    return !empty($result);
}

function hasAutoIncrement(string $table, string $column = 'id'): bool
{
    $databaseName = DB::getDatabaseName();
    $result = DB::select("
        SELECT EXTRA
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
            AND COLUMN_NAME = ?
            AND EXTRA = 'auto_increment'
    ", [$databaseName, $table, $column]);

    return !empty($result);
}

/**
 * Obtém as chaves estrangeiras de uma tabela.
 *
 * @author Maicon Cerutti
 *
 * @param  string  $table  Nome da tabela
 * @return array<string>
 */
function getForeignKeys(string $table): array
{
    $databaseName = DB::getDatabaseName();

    $foreignKeys = DB::select('
        SELECT CONSTRAINT_NAME
        FROM information_schema.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
            AND REFERENCED_TABLE_NAME IS NOT NULL
    ', [$databaseName, $table]);

    $rawKeys = array_column($foreignKeys, 'CONSTRAINT_NAME');

    /** @var list<string> $keys */
    $keys = array_values(array_filter(
        array_map(
            static function (mixed $v): ?string {
                return is_scalar($v) || $v === null ? (string) $v : null;
            },
            $rawKeys
        )
    ));

    /** @var array<string> */
    return array_combine($keys, $keys) ?: [];
}

/**
 * Retorna uma lista de unique keys de uma tabela no banco de dados.
 *
 * @param  string  $table  Nome da tabela
 * @return array<string>
 */
function getUniqueKeys(string $table): array
{
    $databaseName = DB::getDatabaseName();

    $results = DB::select("
        SELECT CONSTRAINT_NAME
        FROM information_schema.TABLE_CONSTRAINTS
        WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
            AND CONSTRAINT_TYPE = 'UNIQUE'
    ", [$databaseName, $table]);

    $rawKeys = array_column($results, 'CONSTRAINT_NAME');

    /** @var list<string> $keys */
    $keys = array_values(array_filter(
        array_map(
            static function (mixed $v): ?string {
                return is_scalar($v) || $v === null ? (string) $v : null;
            },
            $rawKeys
        )
    ));

    /** @var array<string> */
    return array_combine($keys, $keys) ?: [];
}

/**
 * Retorna uma lista de chaves primárias de uma tabela no banco de dados.
 *
 * @param  string  $table  Nome da tabela
 * @return array<string>
 */
function getPrimaryKeyColumns(string $table): array
{
    $databaseName = DB::getDatabaseName();

    $primaryKey = DB::select("
        SELECT COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
            AND CONSTRAINT_NAME = 'PRIMARY'
    ", [$databaseName, $table]);

    $rawKeys = array_column($primaryKey, 'COLUMN_NAME');

    /** @var list<string> $keys */
    $keys = array_values(array_filter(
        array_map(
            static function (mixed $v): ?string {
                return is_scalar($v) || $v === null ? (string) $v : null;
            },
            $rawKeys
        )
    ));

    /** @var array<string> */
    return array_combine($keys, $keys) ?: [];
}
