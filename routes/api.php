<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//Route Auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'registerOwner']);

Route::middleware(['jwt-auth'])->group(function(){
    //Routes Layanan
    Route::get('/layanans', [LayananController::class, 'show']);
    Route::get('layanans/{id}', [LayananController::class, 'showById']);
    Route::post('/layanan', [LayananController::class, 'create']);
    Route::put('/layanan/{id}', [LayananController::class, 'update']);
    Route::delete('/layanan/{id}', [LayananController::class, 'delete']);

    //Routes Pesanan
    Route::get('/pesanan', [PesananController::class, 'index']);
    Route::get('/pesanans', [PesananController::class, 'show']);
    Route::get('/pesanans/{id}', [PesananController::class, 'showById']);
    Route::post('/pesanan', [PesananController::class, 'create']);
    Route::put('/pesanan/{id}', [PesananController::class, 'update']);
    Route::delete('/pesanan/{id}', [PesananController::class, 'delete']);
    Route::patch('/pesanan/status/{id}', [PesananController::class, 'updateStatus']);

    //Routes Laporan
    Route::get('/laporans', [LaporanController::class, 'show']);
    Route::post('/laporans/tanggal', [LaporanController::class, 'showByTanggal']);
    Route::post('/laporan', [LaporanController::class, 'createLaporan']);
});

Route::middleware(['jwt-auth', 'role-owner'])->group(function(){
    Route::post('/register/karyawan', [AuthController::class, 'register']);
}); 


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });