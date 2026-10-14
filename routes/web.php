<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\OrdersController;

// 1. Halaman utama (Katalog Menu Pelanggan)
Route::get('/', [OrdersController::class, 'index'])->name('customer.index');
Route::post('/checkout', [OrdersController::class, 'store'])->name('customer.checkout');

// 2. Arahkan dashboard utama Breeze langsung ke Admin Dashboard Rekap Pesanan
Route::get('/dashboard', [OrdersController::class, 'adminDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. Grup Rute Admin (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Alias route untuk admin dashboard & update status pesanan
    Route::get('/admin/dashboard', [OrdersController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::patch('/admin/orders/{id}/status', [OrdersController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    
    // CRUD Event
    Route::resource('/admin/events', EventsController::class);
});

require __DIR__.'/auth.php';
