<?php

use Illuminate\Database\Migrations\Migration;
use Database\Seeders\Tenants\NotificationSettingsSeeder;
use Database\Seeders\Tenants\NotificationTypesSeeder;

return new class() extends Migration
{

    /**
     * NOTE - Apagavel após release
     * 
     * @return [type]
     */
    public function up()
    {
        (new NotificationTypesSeeder())->run();
        (new NotificationSettingsSeeder())->run();
    }

    public function down()
    {
        //NOTE - Irreversible migration
    }
};
