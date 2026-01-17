<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireClient
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // must be client
        if (!$user || $user->role !== 'client') {
            abort(403);
        }

        // suspended client can't access
        if (($user->approval_status ?? 'approved') === 'suspended') {
            abort(403);
        }

        return $next($request);
    }
}
