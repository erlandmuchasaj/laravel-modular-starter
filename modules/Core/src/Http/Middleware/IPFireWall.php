<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Protecting Site with IP Intelligence
 */
class IPFireWall
{
    /**
     * $url
     */
    private static string $url = 'https://api.ipapi.com/api/{ip}?access_key={token}&security=1';
    // private static string $url = "https://api.ipstack.com/api/{ip}?access_key={token}&security=1"; # alias

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! config('services.ip.enabled')) {
            return $next($request);
        }

        $ip = $request->ip();
        $insecureRequest = Cache::rememberForever("ip_firewall_{$ip}", function () use ($ip) {
            // build parameters
            $key = config('services.ip.key');
            $fullUrl = strtr(self::$url, [
                '{ip}' => $ip,
                '{token}' => $key,
            ]);

            // make request
            $client = new Client(['verify' => false]);

            try {
                $response = $client->request('GET', $fullUrl);
                $data = (array) Utils::jsonDecode($response->getBody()->getContents(), true);
            } catch (Exception $e) {
                Log::warning('Exception error on IPFireWall: ', [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]);

                return false;
            }

            if (! array_key_exists('error', $data)) {
                $code = $data['error']['code'] ?? '';
                $type = $data['error']['type'] ?? '';
                $info = $data['error']['info'] ?? '';

                // $message = "IPFireWall request failed: [{$code}:{$type}] => {$info}.";

                Log::warning('IPFireWall request failed: ', [
                    'code' => $code,
                    'type' => $type,
                    'info' => $info,
                ]);

                return false;
            }

            if (! array_key_exists('security', $data)) {
                return false;
            }

            /**
             * @TODO: where we can also check for proxy (is_proxy), crawler (is_crawler) and TOR browser (is_tor).
             */
            return 'high' === $data['security']['threat_level'];
        });

        return $insecureRequest ? abort(403, __('Request blocked by firewall!')) : $next($request);
    }
}
