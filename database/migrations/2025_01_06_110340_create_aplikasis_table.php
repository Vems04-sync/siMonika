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
        Schema::create('aplikasis', function (Blueprint $table) {
            $table->id('id_aplikasi');
            $table->string('nama');
            $table->string('opd');
            $table->text('uraian')->nullable();
            $table->integer('tahun_pembuatan')->nullable();
            $table->string('jenis');
            $table->string('basis_aplikasi');
            $table->string('bahasa_framework')->nullable();
            $table->string('database')->nullable();
            $table->string('pengembang');
            $table->string('lokasi_server');
            $table->string('status_pemakaian');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplikasis');
    }
};
