<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\ItemPenjualanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/pelanggan', [PelangganController::class, 'index']);
Route::get('/pelanggan/{pelanggan_id}', [PelangganController::class, 'show']);
Route::patch('/pelanggan/{pelanggan_id}', [PelangganController::class, 'update']);
Route::delete('/pelanggan/{pelanggan_id}', [PelangganController::class, 'destroy']);
Route::post('/pelanggan', [PelangganController::class, 'store']);

Route::get('/barang', [BarangController::class, 'index']);
Route::get('/barang/{barang_id}', [BarangController::class, 'show']);
Route::post('/barang', [BarangController::class, 'store']);
Route::patch('/barang/{barang_id}', [BarangController::class, 'update']);
Route::delete('/barang/{barang_id}', [BarangController::class, 'destroy']);

Route::get('/penjualan', [PenjualanController::class, 'index']);
Route::get('/penjualan/{penjualan_id}', [PenjualanController::class, 'show']);
Route::post('/penjualan', [PenjualanController::class, 'store']);
Route::patch('/penjualan/{penjualan_id}', [PenjualanController::class, 'update']);
Route::delete('/penjualan/{penjualan_id}', [PenjualanController::class, 'destroy']);

Route::get('/item-penjualan', [ItemPenjualanController::class, 'index']);
Route::get('/penjualan/{penjualan_id}/item-penjualan/{item_penjualan_id}', [ItemPenjualanController::class, 'show']);
Route::post('/penjualan/{penjualan_id}/item-penjualan', [ItemPenjualanController::class, 'store']);
Route::patch('/penjualan/{penjualan_id}/item-penjualan/{item_penjualan_id}', [ItemPenjualanController::class, 'update']);
Route::delete('/penjualan/{penjualan_id}/item-penjualan/{item_penjualan_id}', [ItemPenjualanController::class, 'destroy']);
