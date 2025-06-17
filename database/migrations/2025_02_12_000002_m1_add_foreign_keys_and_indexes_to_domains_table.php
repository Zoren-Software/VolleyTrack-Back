<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('domains')) {
            Schema::table('domains', function (Blueprint $table) {
                if (!hasAutoIncrement('domains')) {
                    DB::statement('ALTER TABLE domains MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                }

                if (!hasForeignKeyExist('domains', 'domains_tenant_id_foreign')) {
                    $table->foreign('tenant_id', 'domains_tenant_id_foreign')
                        ->references('id')
                        ->on('tenants')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');
                }

                if (Schema::hasColumn('domains', 'domain') && !hasIndexExist('domains', 'domains_domain_unique')) {
                    $table->unique('domain');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('domains')) {
            Schema::table('domains', function (Blueprint $table) {
                if (hasForeignKeyExist('domains', 'domains_tenant_id_foreign')) {
                    $table->dropForeign('domains_tenant_id_foreign');
                }
            });
        }
    }
};
