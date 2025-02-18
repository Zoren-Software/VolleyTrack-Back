<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('personal_access_tokens')) {
            Schema::table('personal_access_tokens', function (Blueprint $table) {

                // Verificar se o campo ID possui AUTO_INCREMENT
                if (!hasAutoIncrement('personal_access_tokens')) {
                    DB::statement('ALTER TABLE personal_access_tokens MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                }

                if (!hasIndexExist('personal_access_tokens', 'personal_access_tokens_tokenable_type_tokenable_id_index')) {
                    $table->index(['tokenable_type', 'tokenable_id'], 'personal_access_tokens_tokenable_type_tokenable_id_index');
                }

                if (!hasIndexExist('personal_access_tokens', 'personal_access_tokens_token_unique')) {
                    $table->unique('token', 'personal_access_tokens_token_unique');
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
        if (Schema::hasTable('personal_access_tokens')) {
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                if (hasIndexExist('personal_access_tokens', 'personal_access_tokens_tokenable_type_tokenable_id_index')) {
                    $table->dropIndex('personal_access_tokens_tokenable_type_tokenable_id_index');
                }
            });
        }
    }
};
