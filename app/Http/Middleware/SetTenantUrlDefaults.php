<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetTenantUrlDefaults
{
    /**
     * Set URL::defaults for the {tenant} route parameter so that
     * all route() calls automatically include the current tenant slug.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($tenant = $request->route('tenant')) {
            URL::defaults(['tenant' => $tenant]);
        }

        return $next($request);
    }
}
