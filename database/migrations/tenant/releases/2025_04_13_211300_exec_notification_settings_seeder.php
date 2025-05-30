<?php

use Database\Seeders\Tenants\NotificationSettingsSeeder;
use Database\Seeders\Tenants\NotificationTypesSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * NOTE - Apagavel após release
     *
     * @return void
     */
    public function up(): void
    {
        (new NotificationTypesSeeder)->run();
        (new NotificationSettingsSeeder)->run();
    }

    /**
     * NOTE - Apagável na próxima versão
     *
     * @return void
     */
    public function down(): void
    {
        // NOTE - Irreversible migration
    }
};
