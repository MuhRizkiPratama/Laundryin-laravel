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
}