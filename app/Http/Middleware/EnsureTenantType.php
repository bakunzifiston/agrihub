<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantType
{
    /**
     * Ensure user only accesses their tenant's routes.
     */
    public function handle(Request $request, Closure $next, string $tenantType): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('home');
        }

        if ($user->tenant_type !== $tenantType) {
            return redirect()->route('home')->with('error', 'Access denied. You cannot access this area.');
        }

        return $next($request);
    }
}
