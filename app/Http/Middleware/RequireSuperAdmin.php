<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class RequireSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) abort(403);

        if (($user->approval_status ?? User::APPROVAL_APPROVED) === User::APPROVAL_SUSPENDED) {
            abort(403);
        }

        if ($user->role !== User::ROLE_SUPER_ADMIN || !$user->hasRole(User::ROLE_SUPER_ADMIN)) {
            abort(403);
        }

        return $next($request);
    }
}
