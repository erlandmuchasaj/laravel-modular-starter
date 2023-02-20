<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Modules\Core\Exceptions\GeneralException;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null ...$guards
     * @return Response
     *
     * @throws GeneralException
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (! $request->expectsJson()) {
                    return redirect(RouteServiceProvider::HOME);
                }
                throw new GeneralException(__('You are already authenticated'), Response::HTTP_BAD_REQUEST);
            }
        }

        return $next($request);
    }
}
