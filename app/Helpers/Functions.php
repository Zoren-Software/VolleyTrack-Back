<?php

function appHost()
{
    return env('APP_HOST', 'volleytrack.com');
}

function appVersion()
{
    $composerJson = file_get_contents(base_path('composer.json'));

    $composerVersao = json_decode($composerJson, true)['version'] ?? null;

    return trim($composerVersao);
}

function hasForeignKeyExist($table, $nameForeignKey)
{
    $conn = Schema::getConnection()->getDoctrineSchemaManager();

    $foreignKeys = array_map(function ($key) {
        return $key->getName();
    }, $conn->listTableForeignKeys($table));

    return in_array($nameForeignKey, $foreignKeys);
}

function hasIndexExist($table, $nameIndex)
{
    $conn = Schema::getConnection()->getDoctrineSchemaManager();

    $index = array_map(function ($key) {
        return $key->getName();
    }, $conn->listTableIndexes($table));

    return in_array($nameIndex, $index);
}

function hasEventExist($eventName)
{
    // Obter o nome do banco de dados do tenant ativo
    $dbName = DB::connection()->getDatabaseName();

    if (!$dbName) {
        throw new Exception('Nenhum banco de dados encontrado para o tenant ativo.');
    }

    $result = DB::connection()->select('
        SELECT EVENT_NAME
        FROM INFORMATION_SCHEMA.EVENTS
        WHERE EVENT_SCHEMA = ? 
            AND EVENT_NAME = ?
    ', [$dbName, $eventName]);

    return !empty($result);
}
