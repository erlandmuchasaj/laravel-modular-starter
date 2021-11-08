<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class LocaleMiddleware.
 */
class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Locale is enabled and allowed to be changed
        if (config('app.locale_status')) {
            $locale = config('app.locale');
            if ($request->expectsJson()) {
                //this is for API  localization
                if ($request->hasHeader('X-Language') && locales()->has($request->header('X-Language'))) {
                    $locale = $request->header('X-Language');
                }
            } else {
                // this is for server side localization
                if (session()->has('locale') && locales()->has(session()->get('locale'))) {
                    $locale = session()->get('locale');
                }
            }
            setAllLocale($locale);
        }

        return $next($request);
    }
}
