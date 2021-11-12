<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckForDemoMode
{
    /**
     * @var array
     */
    protected array $disallowed = [
        'confirm',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (config('app.demo')) {
            // Block all login requests that are not login
            if (
                $request->isMethod('post') ||
                $request->isMethod('put') ||
                $request->isMethod('patch') ||
                $request->isMethod('delete')
            ) {
                abort_if($request->path() !== 'login', Response::HTTP_UNAUTHORIZED);
            }

            // Block any other specific get requests that may alter data
            if ($request->isMethod('get')) {
                collect($this->disallowed)
                    ->each(function ($item) use ($request) {
                        if (str_contains($request->path(), $item)) {
                            abort(Response::HTTP_UNAUTHORIZED);
                        }
                    });
            }
        }
        return $next($request);
    }

}
