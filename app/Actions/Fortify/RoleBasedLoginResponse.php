<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Contracts\LoginResponse;

class RoleBasedLoginResponse implements LoginResponse
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user?->role === User::ROLE_SUPER_ADMIN) {
            return redirect()->intended(Route::has('admin.dashboard') ? route('admin.dashboard') : '/admin');
        }

        if ($user?->role === User::ROLE_RESELLER) {
            return redirect()->intended(Route::has('reseller.dashboard') ? route('reseller.dashboard') : '/reseller');
        }

        return redirect()->intended(route('dashboard'));
    }
}
