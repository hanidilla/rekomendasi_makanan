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
        Schema::create('bahan_makanan', function (Blueprint $table) {
            $table->id();
            $table->string('bahan_makanan')->nullable();
            $table->float('berat')->nullable()->comment('gr');
            $table->float('energi')->nullable()->comment('kal');
            $table->float('protein')->nullable()->comment('gr');
            $table->float('lemak')->nullable()->comment('gr');
            $table->float('karbohidrat')->nullable()->comment('gr');
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
        Schema::dropIfExists('bahan_makanan');
    }
};
