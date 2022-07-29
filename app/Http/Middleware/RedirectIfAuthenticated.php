<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Exceptions\GeneralException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     *
     * @return Response|RedirectResponse|JsonResponse
     * @throws GeneralException
     */
    public function handle(Request $request, Closure $next, ...$guards): Response|RedirectResponse|JsonResponse
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (! $request->expectsJson()) {
                    return redirect(RouteServiceProvider::HOME);
                }
                throw new GeneralException(__('You are already authenticated'), ResponseAlias::HTTP_BAD_REQUEST);
            }
        }

        return $next($request);
    }
}
