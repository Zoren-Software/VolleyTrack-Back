<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up()
    {
        $tenantLog = tenant('id');

        // NOTE - Verify if the tenant name has logs in the name
        if (!(strpos($tenantLog, '_logs') !== false)) {
            // NOTE - This script should not be run for non-log tenants
            throw new \Exception("Tenant Log could not be identified with for logs tenant_id: $tenantLog");
        }

        // NOTE - Get Tenant main
        $tenantMain = str_replace('_logs', '', $tenantLog);

        tenancy()->initialize($tenantMain);

        $tables = DB::select('SHOW TABLES');
        
        // NOTE - Return $tables in array
        $tables = array_map(function ($table) {
            return array_values((array) $table)[0];
        }, $tables);

        // NOTE - The migrations table must not contain logs as it follows Laravel standards
        $tables = array_diff($tables, ['migrations']);

        tenancy()->initialize($tenantLog);

        foreach ($tables as $key => $table) {
            if (!Schema::hasTable($table)) {
                Schema::create($table, function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('log_name')->nullable();
                    $table->text('description');
                    $table->nullableMorphs('subject', 'subject');
                    $table->string('event')->nullable();
                    $table->nullableMorphs('causer', 'causer');
                    $table->json('properties')->nullable();
                    $table->uuid('batch_uuid')->nullable();
                    $table->timestamps();
                    $table->index('log_name');
                });
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
