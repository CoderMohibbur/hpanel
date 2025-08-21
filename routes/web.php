<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VpsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\HorizonController;
use App\Http\Controllers\HostingController;
use App\Http\Controllers\EmailPlanController;




//Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

//website hosting
Route::get('/hosting', [HostingController::class, 'index'])
     ->name('hosting.index');

//Domain
Route::prefix('domains')->name('domains.')->group(function () {
    Route::get('/',          [DomainController::class, 'index'])->name('index');     // Domain portfolio
    Route::get('/register',  [DomainController::class, 'register'])->name('register'); // Get a new domain
    Route::get('/transfer',  [DomainController::class, 'transfer'])->name('transfer'); // Transfer domain
});

//Horizon
Route::get('/horizons', [HorizonController::class, 'index'])->name('horizons.index');

//Email Plans
Route::get('/email', [EmailPlanController::class, 'index'])->name('email.plans');

// Alpine থেকে যেটা কল করছো: /checkout/email?plan={slug}&months={1|12|24|48}
Route::get('/checkout/email', [EmailPlanController::class, 'checkout'])->name('email.checkout');

// (Optional) অর্ডার প্লেস/পেমেন্টে যাওয়ার জন্য POST রুট
Route::post('/checkout/email', [EmailPlanController::class, 'placeOrder'])->name('email.placeOrder');

//vps
Route::prefix('vps')->group(function () {
    Route::get('/', [VpsController::class, 'index'])->name('vps.index');
    Route::get('/kvm', [VpsController::class, 'kvm'])->name('vps.kvm');
    Route::get('/game-panel', [VpsController::class, 'gamePanel'])->name('vps.game');
});