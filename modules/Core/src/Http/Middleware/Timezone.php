<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Timezone
{
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        // $default_timezone = (auth()->check()) ? optional(auth()->user())->timezone : 'UTC';

        // $timezone_cookie = request()->cookie('timezone');

        // $visitor_timezone = (auth()->check()) ? optional(auth()->user())->timezone : geoip()->getLocation(request()->ip())->timezone;

        // if (($timezone_cookie  === null) || (!in_array($timezone_cookie, DateTimeZone::listIdentifiers()))) {
        //     setTimezone($visitor_timezone);
        //     return $response->withCookie(cookie()->forever('timezone', $visitor_timezone));
        // } elseif (in_array($timezone_cookie, DateTimeZone::listIdentifiers())) {
        //     setTimezone($timezone_cookie);
        //     return $response->withCookie(cookie()->forever('timezone', $timezone_cookie));
        // } else {
        //     setTimezone($default_timezone);
        //     return $response->withCookie(cookie()->forever('timezone', $default_timezone));
        // }
        return $response;

        // return $next($request)->withCookie('locale', app()->getLocale(), 60 * 24 * 365 * 5);
    }
}
