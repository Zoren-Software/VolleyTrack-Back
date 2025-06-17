<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $tables = [
        'scouts_defense',
        'scouts_reception',
        'scouts_serve',
        'scouts_attack',
        'scouts_block',
        'scouts_set_assist',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasTable($table)) {
                Schema::create($table, function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id');
                    $table->unsignedBigInteger('scout_fundamental_id');
                    $table->integer('total_a');
                    $table->integer('total_b');
                    $table->integer('total_c');
                    $table->integer('total');
                    $table->timestamps();
                    $table->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
