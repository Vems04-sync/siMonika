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
            $table->string('nama_atribut', 100)->unique();
            $table->string('tipe_data');
            $table->text('enum_options')->nullable(); // Tambahkan kolom ini
            $table->timestamps();
        });

        // Tabel pivot untuk menyimpan nilai atribut per aplikasi
        Schema::create('aplikasi_atribut', function (Blueprint $table) {
            $table->unsignedBigInteger('id_aplikasi');
            $table->unsignedBigInteger('id_atribut');
            $table->text('nilai_atribut')->nullable();
            $table->timestamps();

            $table->foreign('id_aplikasi')
                  ->references('id_aplikasi')
                  ->on('aplikasis')
                  ->onDelete('cascade');

            $table->foreign('id_atribut')
                  ->references('id_atribut')
                  ->on('atribut_tambahans')
                  ->onDelete('cascade');

            $table->primary(['id_aplikasi', 'id_atribut']);
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplikasi_atribut');
        Schema::dropIfExists('atribut_tambahans');
    }
};
