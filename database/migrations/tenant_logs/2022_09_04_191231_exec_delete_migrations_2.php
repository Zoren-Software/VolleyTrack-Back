<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    public function up()
    {
        DB::table('migrations')->truncate();
    }

    public function down()
    {
        //
    }
};
