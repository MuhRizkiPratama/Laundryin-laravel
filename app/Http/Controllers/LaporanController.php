<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class LaporanController extends Controller{

    // Menampilkan semua data laporan
    public function show(){
        $laporan = Laporan::all();
        
        if($laporan){
            return response()->json($laporan, 200);
        }
        return response()->json(['messages' => 'Laporan Tidak ditemukan.'], 401);
    }

    // Membuat data laporan
    public function create(Request $request){
        $tanggal = $request->input('tanggal'); 

        // Hitung jumlah pesanan
        $jumlahPesanan = Pesanan::where('tanggal_pemesanan', $tanggal)->count();

        // Hitung total pendapatan
        $totalPendapatan = Pesanan::where('tanggal_pemesanan', $tanggal)->sum('total_harga');

        $laporan = Laporan::where('tanggal_transaksi', $tanggal)->first();

        if (!$laporan) {
            // Tambah laporan jika belum ada
            $laporan = Laporan::create([
                'tanggal_transaksi' => $tanggal,
                'jumlah_pesanan' => $jumlahPesanan,
                'total_pendapatan' => $totalPendapatan,
            ]);
        } else {
            // Update jika laporan sudah ada
            $laporan->jumlah_pesanan = $jumlahPesanan;
            $laporan->total_pendapatan = $totalPendapatan;
            $laporan->save();
        }

        return response()->json(['message' => 'Laporan berhasil dibuat atau diperbarui']);
    }


    // Menampilkan data laporan by tanggal
    public function showByTanggal(Request $request){
        $tanggal = $request->input('tanggal');
        
        $laporan = Laporan::where('tanggal_transaksi', $tanggal)->first();
    
        if ($laporan) {
            return response()->json($laporan);
        } else {
            return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
        }
    }
}