<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;

class RememberLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request)->withCookie('locale', app()->getLocale(), 60 * 24 * 365 * 5);
    }
}
