<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireReseller
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // must be reseller
        if (!$user || $user->role !== 'reseller') {
            abort(403);
        }

        $status = $user->approval_status ?? 'pending';

        // allow the pending page even when not approved (avoid redirect loop)
        if ($request->routeIs('reseller.pending')) {
            return $next($request);
        }

        // only approved reseller can access reseller panel routes
        if ($status !== 'approved') {
            return redirect()->route('reseller.pending');
        }

        return $next($request);
    }
}
