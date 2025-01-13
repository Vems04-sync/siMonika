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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('aktivitas');
            $table->enum('tipe_aktivitas', ['create', 'update', 'delete', 'login', 'logout']);
            $table->string('modul');
            $table->text('detail')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id_user')
                  ->on('penggunas')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
