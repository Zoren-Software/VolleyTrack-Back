<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        if (!Schema::hasTable('fundamental_specific_fundamental')) {
            Schema::create('fundamental_specific_fundamental', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('fundamental_id')->index('fundamental_specific_fundamental_fundamental_id_index');
                $table->unsignedBigInteger('specific_fundamental_id')->index('fundamental_specific_fundamental_specific_fundamental_id_index');

                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('fundamental_specific_fundamental');
    }
};
