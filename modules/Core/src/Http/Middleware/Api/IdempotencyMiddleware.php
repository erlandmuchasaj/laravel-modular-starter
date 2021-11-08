<?php

namespace Modules\Core\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class IdempotencyMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->method() == 'GET' || $request->method() == 'DELETE') {
            return $next($request);
        }

        $requestId = strval($request->header(config('app.idempotency.key')));
        if (!$requestId) {
            return $next($request);
        }

        if (Cache::has($requestId)) {
            return Cache::get($requestId);
        }

        $response = $next($request);

        $response->header(config('app.idempotency.key'), $requestId);
        Cache::put($requestId, $response, config('app.idempotency.cache_time'));

        return $response;
    }

}
