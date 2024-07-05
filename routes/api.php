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
Route::middleware('throttle:30,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'registerOwner']);
    Route::get('/oauth/google', [AuthController::class, 'loginWithGoogle']);
    Route::get('logout', [AuthController::class, 'logout'])->middleware('jwt-auth');
});

Route::middleware(['jwt-auth', 'throttle:30,1'])->group(function(){
    //Routes Layanan
    Route::get('/layanan', [LayananController::class, 'show']);
    Route::get('layanan/{id}', [LayananController::class, 'showById']);
    Route::post('/layanan', [LayananController::class, 'create']);
    Route::put('/layanan/{id}', [LayananController::class, 'update']);
    Route::delete('/layanan/{id}', [LayananController::class, 'delete']);

    //Routes Pesanan
    Route::get('/pesanan', [PesananController::class, 'show']);
    Route::post('/pesanan', [PesananController::class, 'create']);
    Route::delete('/pesanan/{id}', [PesananController::class, 'delete']);
    Route::patch('/pesanan/status/{id}', [PesananController::class, 'updateStatus']);
    Route::get('/pesanan/selesai', [PesananController::class, 'showSelesai']);
    Route::get('/pesanan/proses', [PesananController::class, 'showProses']);


    //Routes Laporan
    Route::get('/laporan', [LaporanController::class, 'show']);
    Route::post('/laporan', [LaporanController::class, 'create']);
    Route::get('/laporan/tanggal', [LaporanController::class, 'showByTanggal']);
});

Route::middleware(['jwt-auth', 'role-owner', 'throttle:30,1'])->group(function(){
    //Routes Karyawan
    Route::get('/karyawan', [AuthController::class, 'showByRole']);
    Route::get('/karyawan{id}', [AuthController::class, 'showById']);
    Route::post('/karyawan', [AuthController::class, 'register']);
    Route::put('/karyawan/{id}', [AuthController::class, 'update']);
    Route::delete('karyawan/{id}', [AuthController::class, 'delete']);
}); 


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

