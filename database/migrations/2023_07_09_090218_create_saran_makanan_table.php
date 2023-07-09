<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saran_makanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kebutuhan_gizi_id');
            $table->json('saran_makanan')->nullable();
            $table->foreign('kebutuhan_gizi_id')->references('id')->on('kebutuhan_gizi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saran_makanan');
    }
};
