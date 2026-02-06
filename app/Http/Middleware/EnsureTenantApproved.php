<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Super admins bypass approval check
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Tenants must be approved to access dashboard
        if (! $user->isApproved()) {
            return redirect()->route('approval.pending');
        }

        return $next($request);
    }
}
