<?php

use Illuminate\Database\Migrations\Migration;
use Database\Seeders\Tenants\NotificationSettingsSeeder;

return new class() extends Migration
{

    /**
     * NOTE - Apagavel após release
     * 
     * @return [type]
     */
    public function up()
    {
        (new NotificationSettingsSeeder())->run();
    }

    public function down()
    {
        //NOTE - Irreversible migration
    }
};
