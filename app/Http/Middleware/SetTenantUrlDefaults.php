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
        $tenantParam = $request->route('tenant')
            ?? (function_exists('tenancy') && tenancy()->initialized ? tenancy()->tenant->getTenantKey() : null)
            ?? auth('owner')->user()?->tenant?->slug
            ?? request()->segment(1);

        if ($tenantParam) {
            $tenantSlug = is_object($tenantParam)
                ? ($tenantParam->slug ?? $tenantParam->getTenantKey())
                : (string) $tenantParam;

            URL::defaults(['tenant' => $tenantSlug]);
        }

        return $next($request);
    }
}
