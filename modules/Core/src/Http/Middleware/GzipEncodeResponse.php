<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GzipEncodeResponse
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        $response = $next($request);

        if (in_array('gzip', $request->getEncodings()) && function_exists('gzencode')) {
            // 5 is a perfect compromise between size and CPU
            $compressed = gzencode($response->getContent(), 5);

            // Get response length
            $response->setContent($compressed);

            $response->headers->add([
                'Content-Encoding' => 'gzip',
                'Content-Length' => strlen($compressed),
            ]);
        }

        return $response;
    }
}
