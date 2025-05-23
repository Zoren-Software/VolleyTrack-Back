<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('team_levels')) {
            Schema::create('team_levels', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('description')
                    ->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('team_levels');
    }
};
