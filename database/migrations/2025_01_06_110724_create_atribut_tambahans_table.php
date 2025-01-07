<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('atribut_tambahans', function (Blueprint $table) {
            $table->id('id_atribut');
            $table->unsignedBigInteger('id_aplikasi');
            $table->string('nama_atribut', 100);
            $table->text('nilai_atribut')->nullable();
            $table->timestamps();
    
            $table->foreign('id_aplikasi')->references('id_aplikasi')->on('aplikasis')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atribut_tambahans');
    }
};
