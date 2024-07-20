<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Spatie\Permission\PermissionRegistrar;

class ClearPermissionCache
{
    public function handle($request, Closure $next)
    {
        // Bersihkan cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return $next($request);
    }
}
