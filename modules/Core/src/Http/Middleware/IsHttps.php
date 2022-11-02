<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class IsHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // when we go live we might want to force SSL
        // on the requests and responses
        if (isSSL() && app()->environment('production')) {
            URL::forceScheme('https');
        }

        // if (app()->environment('production') && $request->isSecure()) {
        //     URL::forceScheme('https');
        // }

        return $response = $next($request);
    }
}
