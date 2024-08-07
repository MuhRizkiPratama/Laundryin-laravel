<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pesanan;
use App\Models\Pelanggan;
use App\Models\Layanan;
use Carbon\Carbon;

class PesananController extends Controller
{   
    // Menampilkan semua data pesanan dengan relasi pelanggan dan layanan
    public function show(){
        $pesanan = Pesanan::with(['pelanggan', 'layanan'])->get();

        return response()->json($pesanan);
    }

    // Menampilkan by status selesai
    public function showSelesai(){
        // Mengambil data pesanan dengan relasi pelanggan dan layanan yang statusnya 'selesai'
        $pesanan = Pesanan::with(['pelanggan', 'layanan'])->where('status', 'selesai')->get();
    
        return response()->json($pesanan);
    }

    // Menampilkan by status proses
    public function showProses(){
        // Mengambil data pesanan dengan relasi pelanggan dan layanan yang statusnya 'proses'
        $pesanan = Pesanan::with(['pelanggan', 'layanan'])->where('status', 'proses')->get();
    
        return response()->json($pesanan);
    }    
    
    // Membuat data pesanan
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'no_hp' => ['required', 'digits:12', 'numeric'],
            'alamat' => 'required|string',
            'id_layanan' => 'required|exists:layanan,id',
            'jumlah' => 'required|integer'
        ]);

        // Jika inputan salah
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $validated = $validator->validated();

        // Memeriksa apakah pelanggan sudah ada berdasarkan no_hp
        $pelanggan = Pelanggan::where('no_hp', $validated['no_hp'])->first();

        if (!$pelanggan) {
            // Menambah data pelanggan jika belum ada
            $pelanggan = Pelanggan::create([
                'nama' => $validated['nama'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat']
            ]);
        }

        // Mendapatkan data layanan 
        $layanan = Layanan::find($validated['id_layanan']);    

        // Menghitung total harga
        $total_harga = $layanan->harga * $validated['jumlah'];

        // Menambahkan data pesanan
        $pesanan = Pesanan::create([
            'id_pelanggan' => $pelanggan->id,
            'id_layanan' => $layanan->id,
            'tanggal_pemesanan' => now(),
            'tanggal_selesai' => now()->addHours($layanan->durasi),
            'jumlah' => $validated['jumlah'],
            'total_harga' => $total_harga
        ]);

        return response()->json(['message' => 'Pesanan berhasil dibuat', 'pesanan' => $pesanan], 201);
    }

    // Mengapus data pesanan berdasarkan id
    public function delete($id){
        $pesanan = Pesanan::find($id);

        if (!$pesanan) {
            return response()->json(['message' => 'Data Pesanan Tidak Ditemukan.'], 404);
        }

        $pesanan->delete();

        return response()->json(['message' => 'Data Pesanan Berhasil Dihapus.'], 200);
    }

    // Mengubah Status dari Proses ke Selesai
    public function updateStatus($id){
        $pesanan = Pesanan::find($id);

        if (!$pesanan) {
            return response()->json(['message' => 'Data Pesanan Tidak Ditemukan.'], 404);
        }

        $pesanan->status = 'selesai';

        $pesanan->save();

        return response()->json(['message' => 'Status Pesanan Berhasil Diubah Menjadi Selesai.'], 200);
    }

}
