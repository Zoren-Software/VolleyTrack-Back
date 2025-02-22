<?php

use App\Exceptions\PermissionConfigNotLoadedException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            // Lançando a exceção personalizada
            throw new PermissionConfigNotLoadedException();
        }

        if (!Schema::hasTable($tableNames['permissions'])) {
            Schema::create($tableNames['permissions'], function (Blueprint $table) use ($tableNames) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();

                if (!hasIndexExist($tableNames['permissions'], 'permissions_name_guard_name_unique')) {
                    $table->unique(['name', 'guard_name'], 'permissions_name_guard_name_unique');
                }
            });
        }
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        Schema::dropIfExists($tableNames['permissions']);
    }
};
