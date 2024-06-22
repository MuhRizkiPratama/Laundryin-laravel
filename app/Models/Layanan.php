<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $fillable = ['nama', 'deskripsi', 'durasi', 'harga', 'unit'];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_layanan', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($layanan) {
            // Hapus semua pesanan yang terkait dengan layanan ini
            $layanan->pesanan()->delete();
        });
    }
}
