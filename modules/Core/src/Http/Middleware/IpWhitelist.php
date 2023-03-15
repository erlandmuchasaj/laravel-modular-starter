<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class IpWhitelist
{
    /**
     * @var string[]
     */
    public array $whitelistIps = [
        '192.168.0.5',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response|RedirectResponse) $next
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (! app()->environment('production')) {
            return $next($request);
        }

        if (! in_array(request()->ip(), $this->whitelistIps)) {
            abort(ResponseAlias::HTTP_FORBIDDEN, __('Forbidden'));
        }

        return $next($request);
    }
}
