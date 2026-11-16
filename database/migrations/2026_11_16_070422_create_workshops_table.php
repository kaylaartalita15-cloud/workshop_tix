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
    Schema::create('workshops', function (Blueprint $table) {
        $table->id();
        $table->string('nama_workshop');
        $table->text('deskripsi');
        $table->string('pemateri');
        $table->date('tanggal');
        $table->time('waktu');
        $table->string('lokasi');
        $table->decimal('harga', 12, 2);
        $table->integer('kuota');
        $table->string('status')->default('aktif');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
