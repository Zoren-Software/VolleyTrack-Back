<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('external_access_tokens')) {
            Schema::create('external_access_tokens', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary(); // ID sem auto incremento
                $table->string('token');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_access_tokens');
    }
};