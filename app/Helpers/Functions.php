<?php

use Illuminate\Support\Facades\DB;

function appHost()
{
    return env('APP_HOST', 'volleytrack.com');
}

function appVersion()
{
    $composerJson = file_get_contents(base_path('composer.json'));

    return trim(json_decode($composerJson, true)['version'] ?? '');
}

function hasForeignKeyExist($table, $nameForeignKey)
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

function hasIndexExist($table, $nameIndex)
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

function hasEventExist($eventName)
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

function hasAutoIncrement($table, $column = 'id')
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

    return array_column($foreignKeys, 'CONSTRAINT_NAME');
}

/**
 * Retorna uma lista de unique keys de uma tabela no banco de dados.
 *
 * @param  string  $table  Nome da tabela
 * @return array Lista de unique keys
 */
function getUniqueKeys($table)
{
    $databaseName = DB::getDatabaseName();

    $results = DB::select("
        SELECT CONSTRAINT_NAME
        FROM information_schema.TABLE_CONSTRAINTS
        WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
            AND CONSTRAINT_TYPE = 'UNIQUE'
    ", [$databaseName, $table]);

    return array_column($results, 'CONSTRAINT_NAME');
}

/**
 * Retorna uma lista de chaves primárias de uma tabela no banco de dados.
 *
 * @param  string  $table  Nome da tabela
 * @return array Lista de unique keys
 */
function getPrimaryKeyColumns($table): array
{
    $databaseName = DB::getDatabaseName();

    $primaryKey = DB::select("
            SELECT COLUMN_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
            AND CONSTRAINT_NAME = 'PRIMARY'
        ", [$databaseName, $table]);

    return array_column($primaryKey, 'COLUMN_NAME');
}
