<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(config('activitylog.table_name'))) {
            Schema::table(config('activitylog.table_name'), function (Blueprint $table) {
                if (!hasAutoIncrement(config('activitylog.table_name'))) {
                    DB::statement("ALTER TABLE " . config('activitylog.table_name') . " MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
                }
                
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
};
