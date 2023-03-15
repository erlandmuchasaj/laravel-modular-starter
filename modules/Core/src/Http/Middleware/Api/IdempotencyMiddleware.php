<?php

namespace Modules\Core\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * In the context of REST APIs,
 * when making multiple identical requests has the same effect as making a single request
 * then that REST API is called idempotent.
 *
 * @TODO: We have to make our APIs fault-tolerant in such a way that the duplicate requests do not leave the system unstable.
 */
class IdempotencyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // the following method are idempotent so no need to process them.
        if (in_array($request->method(), ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS', 'TRACE'], true)) {
            return $next($request);
        }

        $requestId = strval($request->header(config('app.idempotency.key')));
        if (! $requestId) {
            return $next($request);
        }

        if (Cache::has($requestId)) {
            return Cache::get($requestId);
        }

        $response = $next($request);

        $response->header(config('app.idempotency.key'), $requestId);

        // if it's not an error, cache it
        // if (in_array($response->status(), [Response::HTTP_OK, Response::HTTP_CREATED, Response::HTTP_ACCEPTED, Response::HTTP_NO_CONTENT])) {
        Cache::put($requestId, $response, config('app.idempotency.cache_time'));
        // }

        return $response;
    }
}
