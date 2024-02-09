<?php

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MasakanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->id_level == 1) {
            return redirect()->route('order');
        } else {
            return redirect()->route('home');
        }
    }

    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['checkUserLevel:2,3'])->group(function () {
    Route::get('/user', [HomeController::class, 'index'])->name('home');
    Route::get('/createusers', [RegisterController::class, 'showRegistrationForm'])->name('create.users');
    Route::post('/storeusers', [HomeController::class, 'storeUsers'])->name('store.users');
    Route::post('/deleteusers', [HomeController::class, 'deleteUsers'])->name('delete.users');
    Route::get('/editusers{id}', [HomeController::class, 'editUsers'])->name('edit.users');
    Route::post('/updateusers', [HomeController::class, 'updateUsers'])->name('update.users');
});

Route::middleware(['checkUserLevel:3'])->group(function () {
    Route::get('/menu', [HomeController::class, 'menu'])->name('menu');
    Route::get('/createmenu', [HomeController::class, 'createMenu'])->name('create.menu');
    Route::post('/storemenu', [HomeController::class, 'storeMenu'])->name('store.menu');
    Route::post('/deletemenu', [HomeController::class, 'deleteMenu'])->name('delete.menu');
    Route::get('/editmenu{id}', [HomeController::class, 'editMenu'])->name('edit.menu');
    Route::post('/updatemenu', [HomeController::class, 'updateMenu'])->name('update.menu');
    Route::post('/update-menu-status', [HomeController::class, 'updateMenuStatus'])->name('update.menu.status');

    Route::get('/meja', [HomeController::class, 'meja'])->name('meja');
    Route::get('/createmeja', [HomeController::class, 'createMeja'])->name('create.meja');
    Route::post('/storemeja', [HomeController::class, 'storeMeja'])->name('store.meja');
    Route::post('/deletemeja', [HomeController::class, 'deleteMeja'])->name('delete.meja');
});

Route::middleware(['checkUserLevel:1'])->group(function () {
    Route::get('/order', [OrderController::class, 'index'])->name('order');
    Route::get('/order/riwayat', [OrderController::class, 'riwayatOrder'])->name('riwayat.order');
    Route::get('/createorder', [OrderController::class, 'createOrder'])->name('create.order');
    Route::post('/storeorder', [OrderController::class, 'storeOrder'])->name('store.order');

    Route::middleware(['checkOrderAccess'])->group(function () {
        Route::get('/dorder/{id}', [OrderController::class, 'dorder'])->name('dorder');
    });
    Route::post('/storedorder', [OrderController::class, 'storeDorder'])->name('store.dorder');
    Route::post('/updatedorder', [OrderController::class, 'updateDorder'])->name('update.dorder');
    Route::delete('/deletedorder/{id}', [OrderController::class, 'deleteDorder'])->name('delete.dorder');
    Route::middleware(['checkOrder'])->group(function () {
        Route::get('/makeorder/{nomeja}', [OrderController::class, 'makeOrder'])->name('make.order');
    });
    Route::post('/simpanpesanan', [OrderController::class, 'simpanPesanan'])->name('simpan.pesanan');
});

Route::middleware(['checkUserLevel:2'])->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'transaksi'])->name('transaksi');
    Route::get('/transaksi/riwayat', [TransaksiController::class, 'riwayatTransaksi'])->name('riwayat.transaksi');
    Route::post('/update-status-pembayaran', [TransaksiController::class, 'updateStatusPembayaran'])->name('update.status.pembayaran');
    Route::post('/batal-order', [TransaksiController::class, 'batalOrder'])->name('batal.order');
    Route::post('/proses-order', [TransaksiController::class, 'prosesOrder'])->name('proses.order');
    Route::get('/transaksi/{id}', [TransaksiController::class, 'detailTransaksi'])->name('detail.transaksi');
    Route::post('/pesanan-selesai', [TransaksiController::class, 'pesananSelesai'])->name('pesanan.selesai');
    Route::post('/pesanan-batal', [TransaksiController::class, 'pesananBatal'])->name('pesanan.batal');
});
