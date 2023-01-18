<?php

namespace Modules\Core\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class LocaleMiddleware.
 */
class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = config('app.locale'); // default locale

        // Locale is enabled and allowed to be changed
        if (config('app.locale_status')) {
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
            // setAllLocale($locale);
            // if this locale has not already been set
            if (! App::isLocale($locale)) {
                $lang = locales()->first(function ($value, $key) use ($locale) {
                    return $key === $locale;
                });

                if ($lang) {
                    // set laravel localization (lumen)
                    app('translator')->setLocale($locale);

                    // Set the Laravel locale
                    App::setLocale($locale);

                    // setLocale to use Carbon source locales. Enables diffForHumans() localized
                    Carbon::setLocale($locale);

                    // setLocale for php. Enables ->formatLocalized() with localized values for dates
                    setlocale(LC_TIME, str_replace('-', '_', $lang['code']));

                    /*
                     * Set the session variable for whether the app is using RTL support
                     * For use in the blade directive in BladeServiceProvider
                     */
                    if (! app()->runningInConsole()) {
                        // $lang[2])
                        if ($lang['rtl']) {
                            session(['lang-rtl' => true]);
                        } else {
                            session()->forget('lang-rtl');
                        }
                    }
                }
            }
        }

        // get the response after the request is done
        $response = $next($request);

        // set Content Languages header in the response
        $response->headers->set('Content-Language', $locale);

        // return the response
        return $response;
    }
}
