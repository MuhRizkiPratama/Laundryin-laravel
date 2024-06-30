<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Layanan;

class LayananController extends Controller
{   
    // Menampilkan semua data layanan
    public function show(){
        $layanan = Layanan::all();

        return response()->json($layanan, 200);
    }

    // Menampilkan data layanan by id
    public function showById($id){
        $layanan = Layanan::find($id);
        
        if (!$layanan) {
            return response()->json(['message' => 'Layanan tidak ditemukan'], 404);
        }
        return response()->json($layanan, 200);
    }

    //Menambahkan data
    public function create(request $request){
        // Validasi inputan
        $validator = Validator::make($request->all(),[
            'nama' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:255',
            'durasi' => 'required|numeric',
            'harga' => 'required|numeric',
            'unit' => 'required|in:kg,pcs'
        ]);

        // Jika inputan salah
        if($validator->fails()){
            return response()->json($validator->messages(), 422);
        }

        //Inputan yang benar
        $validated = $validator->validated();

        Layanan::create([
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'],
            'durasi' => $validated['durasi'],
            'harga' => $validated['harga'],
            'unit' => $validated['unit']
        ]);

        return response()->json('Data Layanan Berhasil Ditambahkan.', 201);

    }

    // Mengupdate data berdasarkan id
    public function update(request $request, $id){
        $validator = Validator::make($request->all(),[
            'nama' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:255',
            'durasi' => 'required|numeric',
            'harga' => 'required|numeric',
            'unit' => 'required|in:kg,pcs'
        ]);

        // Jika inputan salah
        if($validator->fails()){
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();

        // Jika data layanan berdasarkan id ditemukan
        $checkData = Layanan::find($id);

        if($checkData){
            Layanan::where('id', $id)
                ->update([
                    'nama' => $validated['nama'],
                    'deskripsi' => $validated['deskripsi'],
                    'durasi' => $validated['durasi'],
                    'harga' => $validated['harga'],
                    'unit' => $validated['unit']
                ]);
    
            return response()->json(['messages' => 'Data Layanan Berhasil Diupdate.'], 201);
        }
        return response()->json(['messages' => 'Data Layanan Tidak Ditemukan.'], 404);
    }
    
    // Mengapus data berdasarkan id
    public function delete($id){
        $layanan = Layanan::find($id);

        if (!$layanan) {
            return response()->json(['message' => 'Data Layanan Tidak Ditemukan.'], 404);
        }

        $layanan->delete();

        return response()->json(['message' => 'Data Layanan Berhasil Dihapus.'], 200);
    }
}