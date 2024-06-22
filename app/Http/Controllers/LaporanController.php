<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller{
    public function show(){
        $laporan = Laporan::all();
        if($laporan){
            return response()->json($laporan, 200);
        }
    return response()->json(['messages' => 'Laporan Tidak ditemukan.'], 401);
    }
}