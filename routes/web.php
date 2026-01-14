<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KonsumenController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Models\Penjualan;

// authentication
Route::get('/', [LoginBasic::class, 'index'])->name('login');
Route::post('/login', [LoginBasic::class, 'login'])->name('login.process');
Route::post('/logout', [LoginBasic::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(
  function () {
    // Main Page Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::put('/user/update/{pk}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{pk}', [UserController::class, 'destroy'])->name('user.destroy');

    // Supplier
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier');
    Route::post('/supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
    Route::put('/supplier/update/{pk}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier/{pk}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    // Konsumen
    Route::get('/konsumen', [KonsumenController::class, 'index'])->name('konsumen');
    Route::post('/konsumen/store', [KonsumenController::class, 'store'])->name('konsumen.store');
    Route::put('/konsumen/update/{pk}', [KonsumenController::class, 'update'])->name('konsumen.update');
    Route::delete('/konsumen/{pk}', [KonsumenController::class, 'destroy'])->name('konsumen.destroy');

    // Produk
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    Route::post('/produk/store', [ProdukController::class, 'store'])->name('produk.store');
    Route::put('/produk/update/{pk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{pk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

    // Pembelian
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('/pembelian/store', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/pembelian/edit/{pk}', [PembelianController::class, 'edit'])->name('pembelian.edit');
    Route::put('/pembelian/update/{pk}', [PembelianController::class, 'update'])->name('pembelian.update');
    Route::delete('/pembelian/{notrs}', [PembelianController::class, 'destroy'])->name('pembelian.destroy');
    Route::get('/pembelian/export/excel', [PembelianController::class, 'excel'])->name('pembelian.export.excel');
    Route::get('/pembelian/export/pdf', [PembelianController::class, 'pdf'])->name('pembelian.export.pdf');

    // Penjualan
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/edit/{notrs}', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::put('/penjualan/update/{pk}', [PenjualanController::class, 'update'])->name('penjualan.update');
    Route::delete('/penjualan/{notrs}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    Route::get('/penjualan/export/excel', [PenjualanController::class, 'excel'])->name('penjualan.export.excel');
    Route::get('/penjualan/export/pdf', [PenjualanController::class, 'pdf'])->name('penjualan.export.pdf');
  }
);
