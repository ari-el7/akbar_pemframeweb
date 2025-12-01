<?php

use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualandetailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/level', [LevelController::class, 'index']);
Route::get('/user', [UserController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/supplier', [SupplierController::class, 'index']);
Route::get('/barang', [BarangController::class, 'index']);
Route::get('/stok', [StokController::class, 'index']);
Route::get('/penjualan', [PenjualanController::class, 'index']);
Route::get('/penjualandetail', [PenjualandetailController::class, 'index']);

Route::get('/user/tambah', [UserController::class, 'tambah']);
Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);

route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);

route::get('/user/hapus/{id}', [UserController::class, 'hapus']);

// Route::get('/hello', function() {
//     return "Hello World";
// });

// Route::get('/world', function () {
// return 'World';
// });

// Route::get('/about', function() {
//     return "NIM = 23.51.0018, Nama = Akbar ATMA Riyahtha";
// }); 

// Route::get('/user/{name}', function ($name) {
//     return 'Nama saya '.$name;
// });

// Route::get('/posts/{post}/comments/{comment}', function
// ($postId, $commentId) {
// return 'Pos ke-'.$postId." Komentar ke-: ".$commentId;
// }); 

// Route::get('/user/{name?}', function ($name=null) {
//     return 'Nama saya '.$name;
// });

// Route::get('/user/{name?}', function ($name='Ariel') {
// return 'Nama saya '.$name;
// });

// Route::view('/Kontak', 'kontak');