<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     * NOTE - Deletar na próxima consolidação de migrations
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('leads');
        Schema::dropIfExists('lead_interactions');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_has_permissions');
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
