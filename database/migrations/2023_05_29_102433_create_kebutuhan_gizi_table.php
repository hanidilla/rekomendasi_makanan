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
        Schema::create('kebutuhan_gizi', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->double('umur')->nullable();
            $table->double('tinggi')->nullable();
            $table->double('berat')->nullable();
            $table->double('stress_fac')->nullable();
            $table->double('activity_fac')->nullable();
            $table->double('kalori')->nullable();
            $table->double('protein')->nullable();
            $table->double('lemak')->nullable();
            $table->double('karbohidrat')->nullable();
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
        Schema::dropIfExists('kebutuhan_gizi');
    }
};
