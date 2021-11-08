<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Protecting Site with IP Intelligence
 */
class IPFireWall
{
    /**
     * $url
     * @var string
     */
    private static string $url = "http://api.ipapi.com/{ip}?access_key={token}&security=1";

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!config('services.ip.enabled')) {
            return $next($request);
        }

        $ip = $request->ip();
        $insecureRequest = Cache::rememberForever("ip_firewall_{$ip}", function() use ($ip) {
            // build parameters
            $key = config('services.ip.key');
            $fullUrl = strtr(self::$url, [
                '{ip}' => $ip,
                '{token}' => $key
            ]);

            // make request
            $client = new Client(['verify' => false]);

            try {
                $response = $client->request('GET', $fullUrl);
                $data = (array) Utils::jsonDecode($response->getBody()->getContents(), true);
            } catch (Exception $e) {
                return false;
            }

            if (!array_key_exists('security', $data)) {
                return false;
            }
            return 'high' === $data['security']['threat_level'];
        });

        return $insecureRequest ? abort(403, 'Request blocked by firewall!') : $next($request);
    }
}
