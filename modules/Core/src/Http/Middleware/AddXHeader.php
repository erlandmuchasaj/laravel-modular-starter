<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddXHeader
{

    /**
     * @var array
     */
    private array $unwantedHeaderList = [
        'X-Powered-By',
        'Server',
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

        // They seem to mess up the tests so disable them
        if (config('app.testing')) {
            return $next($request);
        }

        $this->removeUnwantedHeaders($this->unwantedHeaderList);

        // $response = $next($request);
        // $this->decorateResponse($response);
        // return $response;

        return tap($next($request), function (mixed $response) {
            $this->decorateResponse($response);
        });
    }

    /**
     * @param array $headerList
     */
    private function removeUnwantedHeaders(array $headerList): void
    {
        foreach ($headerList as $header) {
            header_remove($header);
        }
    }

    /**
     * @param mixed $response
     * @return void
     */
    private function decorateResponse(mixed $response): void
    {
        // Check if we should/can add header
        if (method_exists((object) $response,'header')) {
            // Info: https://erlandmuchasaj.tech/
            $response->header('X-Man', 'Ndershkuesi');

            // Info: https://scotthelme.co.uk/a-new-security-header-referrer-policy/
            $response->header('Referrer-Policy', 'no-referrer-when-downgrade');

            // Info: https://scotthelme.co.uk/hardening-your-http-response-headers/#x-content-type-options
            $response->header('X-Content-Type-Options', 'nosniff');

            // Info: https://scotthelme.co.uk/hardening-your-http-response-headers/#x-xss-protection
            $response->header('X-XSS-Protection', '1; mode=block');

            // Info: https://scotthelme.co.uk/hardening-your-http-response-headers/#x-frame-options
            $response->header('X-Frame-Options', 'DENY');

            // Info: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

            // // Info: https://scotthelme.co.uk/content-security-policy-an-introduction/
            // // Generate Here: https://www.cspisawesome.com/content_security_policies
            // $response->header('Content-Security-Policy', "default-src 'self'");
            // // Info: https://scotthelme.co.uk/a-new-security-header-feature-policy/
            // $response->header('Feature-Policy', "geolocation 'none'; midi 'none'; sync-xhr 'none'; microphone 'none'; camera 'none'; magnetometer 'none'; gyroscope 'none'; speaker 'self'; fullscreen 'self'; payment 'none'");
        }

    }

}
