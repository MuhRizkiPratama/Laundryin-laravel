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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_layanan');
            $table->integer('jumlah');
            $table->date('tanggal_pemesanan');
            $table->date('tanggal_selesai');
            $table->enum('status', ['proses', 'selesai'])->default('proses');
            $table->integer('total_harga');
            $table->timestamps();
            
            $table->foreign('id_layanan')->references('id')->on('layanan');
            $table->foreign('id_pelanggan')->references('id')->on('pelanggan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
