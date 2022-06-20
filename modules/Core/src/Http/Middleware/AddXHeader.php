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

        $this->removeUnwantedHeaders($request, $this->unwantedHeaderList);

        return tap($next($request), function (mixed $response) {
            $this->decorateResponse($response);
        });
    }

    /**
     * @param  Request  $request
     * @param  array  $headerList
     */
    private function removeUnwantedHeaders(Request $request, array $headerList): void
    {
        foreach ($headerList as $header) {
            $request->headers->remove($header);
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
            // Info: https://scotthelme.co.uk/hardening-your-http-response-headers

            // Set miscellaneous headers.
            $response->headers->set('X-Man', 'Ndershkuesi', true);
            $response->headers->set('X-Server-Timezone', now()->getTimezone(), true);
            $response->headers->set('X-Server-Timezone-Offset', now()->getOffset(), true);

            /*
             * Server
             *
             * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Server
             *
             * Note: when server is empty string, it will not add to response header
             */
            $response->headers->set('Server', '', true);

            /*
             * X-Content-Type-Options
             *
             * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
             *
             * Available Value: 'nosniff'
             */
            $response->headers->set('X-Content-Type-Options', 'nosniff', true);

            /*
             * X-Download-Options
             *
             * Reference: https://msdn.microsoft.com/en-us/library/jj542450(v=vs.85).aspx
             *
             * Available Value: 'noopen'
             */
            $response->headers->set('X-Download-Options', 'noopen', true);

            /*
             * X-Frame-Options
             *
             * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
             *
             * Available Value: 'deny', 'sameorigin', 'allow-from <uri>'
             */
            $response->headers->set('X-Frame-Options', 'Deny', true);

            /*
             * X-Permitted-Cross-Domain-Policies
             *
             * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Permitted-Cross-Domain-Policies
             *
             * Available Value: 'all', 'none', 'master-only', 'by-content-type', 'by-ftp-filename'
             */
            $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none', true);

            /*
             * X-Powered-By
             *
             * Note: it will not add to response header if the value is empty string.
             *
             * Also, verify that expose_php is turned Off in php.ini. expose_php=false
             * Otherwise the header will still be included in the response.
             *
             * Reference: https://github.com/bepsvpt/secure-headers/issues/58#issuecomment-782332442
             */
            $response->headers->set('X-Powered-By', '', true);

            /*
             * X-XSS-Protection
             *
             * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection
             *
             * Available Value: '1', '0', '1; mode=block'
             */
            $response->headers->set('X-XSS-Protection', '1; mode=block', true);

            /*
             * Referrer-Policy
             *
             * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Referrer-Policy
             *
             * Available Value: 'no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin',
             *                  'same-origin', 'strict-origin', 'strict-origin-when-cross-origin', 'unsafe-url'
             */
            $response->headers->set('Referrer-Policy', 'no-referrer, strict-origin-when-cross-origin', true);

            /*
             * HTTP Strict Transport Security
             *
             * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security
             *
             * Please ensure your website had set up ssl/tls before enable hsts.
             */
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains', true);

            /*
             * Clear-Site-Data
             *
             * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Clear-Site-Data
             * This works only o SSL connections.
             */
            if (config('app.ssl')) {
                $response->headers->set('Clear-Site-Data', 'cache, cookies, storage, executionContexts', true);
            }

            /*
             * Content Security Policy
             *
             * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP
             */
            $response->headers->set('Content-Security-Policy', "default-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; img-src * 'self' data:; font-src * 'self' data: https://fonts.gstatic.com; media-src *; object-src *; prefetch-src *", true);

            /*
             * Permissions Policy
             *
             * Reference: https://w3c.github.io/webappsec-permissions-policy/
             */
            $response->headers->set('Permissions-Policy', "accelerometer=(self), autoplay=(self), camera=(self), cross-origin-isolated=(self), display-capture=(self), document-domain=*, encrypted-media=(self), fullscreen=(self), geolocation=(self), gyroscope=(self), magnetometer=(self), microphone=(self), midi=(self), payment=(self), picture-in-picture=*, publickey-credentials-get=(self), screen-wake-lock=(self), sync-xhr=*, usb=(self), web-share=(self), xr-spatial-tracking=(self)", true);
        }

    }

}
