<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Laporan;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = ['id_layanan', 'id_pelanggan', 'jumlah', 'tanggal_pemesanan', 'tanggal_selesai', 'status', 'total_harga']; 

    public function pelanggan(){
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }

    public function layanan(){
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id');
    }

    protected static function booted(){
        // Event listener untuk menambahkan pesanan baru ke laporan
        static::created(function ($pesanan) {
            $tanggal = $pesanan->tanggal_pemesanan;
            $laporan = Laporan::where('tanggal_transaksi', $tanggal)->first();
        
            if (!$laporan) {
                // Jika belum ada laporan untuk tanggal ini, buat laporan baru
                $laporan = Laporan::create([
                    'tanggal_transaksi' => $tanggal,
                    'jumlah_pesanan' => $pesanan->jumlah,
                    'total_pendapatan' => $pesanan->total_harga,
                ]);
            } else {
                // Jika sudah ada laporan, update jumlah pesanan dan pendapatan
                $laporan->jumlah_pesanan += $pesanan->jumlah;
                $laporan->total_pendapatan += $pesanan->total_harga;
                $laporan->save();
            }
        });
        
    }
}