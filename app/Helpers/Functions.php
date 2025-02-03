<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
    $result = DB::select("
        SELECT CONSTRAINT_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = ?
          AND TABLE_NAME = ?
          AND CONSTRAINT_NAME = ?
    ", [$databaseName, $table, $nameForeignKey]);

    return !empty($result);
}

function hasIndexExist($table, $nameIndex)
{
    $databaseName = DB::getDatabaseName();
    $result = DB::select("
        SELECT INDEX_NAME
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = ?
          AND TABLE_NAME = ?
          AND INDEX_NAME = ?
    ", [$databaseName, $table, $nameIndex]);

    return !empty($result);
}

function hasEventExist($eventName)
{
    $dbName = DB::connection()->getDatabaseName();
    $result = DB::select("
        SELECT EVENT_NAME
        FROM INFORMATION_SCHEMA.EVENTS
        WHERE EVENT_SCHEMA = ? 
          AND EVENT_NAME = ?
    ", [$dbName, $eventName]);

    return !empty($result);
}
