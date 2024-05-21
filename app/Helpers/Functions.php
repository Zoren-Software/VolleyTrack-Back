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