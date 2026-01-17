<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Middleware\RequireReseller;
use App\Http\Middleware\RequireSuperAdmin;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\HostingController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\HorizonController;
use App\Http\Controllers\EmailPlanController;
use App\Http\Controllers\VpsController;

use App\Http\Controllers\Admin\UserAdminController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/hosting', [HostingController::class, 'index'])->name('hosting.index');

Route::prefix('domains')->name('domains.')->group(function () {
    Route::get('/', [DomainController::class, 'index'])->name('index');
    Route::get('/register', [DomainController::class, 'register'])->name('register');
    Route::get('/transfer', [DomainController::class, 'transfer'])->name('transfer');
});

Route::get('/horizons', [HorizonController::class, 'index'])->name('horizons.index');

/*
|--------------------------------------------------------------------------
| Email Plans + Checkout
|--------------------------------------------------------------------------
*/
Route::get('/email', [EmailPlanController::class, 'index'])->name('email.plans');
Route::get('/checkout/email', [EmailPlanController::class, 'checkout'])->name('email.checkout');
Route::post('/checkout/email', [EmailPlanController::class, 'placeOrder'])->name('email.placeOrder');

/*
|--------------------------------------------------------------------------
| VPS Routes
|--------------------------------------------------------------------------
*/
Route::prefix('vps')->name('vps.')->group(function () {
    Route::get('/', [VpsController::class, 'index'])->name('index');
    Route::get('/kvm', [VpsController::class, 'kvm'])->name('kvm');
    Route::get('/game-panel', [VpsController::class, 'gamePanel'])->name('game');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Super Admin Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RequireSuperAdmin::class])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserAdminController::class, 'index'])->name('index');
            Route::get('/{user}/edit', [UserAdminController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserAdminController::class, 'update'])->name('update');
        });
    });

/*
|--------------------------------------------------------------------------
| Reseller Routes (Approved Reseller Only)
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Reseller Routes (Reseller Role Only; Approved can access dashboard)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RequireReseller::class])
    ->prefix('reseller')
    ->name('reseller.')
    ->group(function () {

        // Approved হলে dashboard, না হলে middleware pending page এ পাঠাবে
        Route::get('/', fn () => view('reseller.dashboard'))->name('dashboard');

        // Pending/Rejected/Suspended reseller এখানে সুন্দর মেসেজ দেখবে
        Route::get('/pending', fn () => view('reseller.pending'))->name('pending');
    });


/*
|--------------------------------------------------------------------------
| Unified Dashboard (Jetstream protected) - Role Smart Redirect ✅
|--------------------------------------------------------------------------
| /dashboard এ গেলে:
| - super_admin => /admin
| - reseller    => /reseller
| - client      => client.dashboard view
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        abort(403);
    }

    if ($user->role === 'super_admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'reseller') {
        return redirect()->route('reseller.dashboard');
    }

    // default: client
    return view('client.dashboard');
})->name('dashboard');
