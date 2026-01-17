<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class RequireClient
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) abort(403);

        // must be client (column + spatie must match)
        if ($user->role !== User::ROLE_CLIENT || !$user->hasRole(User::ROLE_CLIENT)) {
            abort(403);
        }

        // suspended client can't access
        if (($user->approval_status ?? User::APPROVAL_APPROVED) === User::APPROVAL_SUSPENDED) {
            abort(403);
        }

        return $next($request);
    }
}
