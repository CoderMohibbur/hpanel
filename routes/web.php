<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HostingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🖥️ Hosting Provisioning Routes
    Route::prefix('hosting')->name('hosting.')->group(function () {
        Route::get('/', [HostingController::class, 'index'])->name('index');
        Route::get('/create', [HostingController::class, 'create'])->name('create');
        Route::post('/', [HostingController::class, 'store'])->name('store');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index'); // Dashboard

    // ✅ Resource routes for users handled by UserController
    Route::resource('users', UserController::class);

    // ✅ Custom toggle status
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
});

require __DIR__ . '/auth.php';
