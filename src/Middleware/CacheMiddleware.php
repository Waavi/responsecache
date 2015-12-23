<?php

namespace Waavi\ResponseCache\Middleware;

use Closure;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Response;
use Waavi\ResponseCache\ResponseCache;

class CacheMiddleware
{
    /**
     * @var \App\Utils\ResponseCache\ResponseCache
     */
    protected $responseCache;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(ResponseCache $responseCache, Config $config)
    {
        $this->responseCache = $responseCache;
        $this->minutes       = $config->get('responsecache.timeout');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Return the cached response if available
        if ($this->responseCache->has($request)) {
            return $this->responseCache->get($request);
        }

        // Cache, log and return the live response:
        $response = $next($request);
        if (get_class($response) === Response::class) {
            $this->responseCache->put($request, $response, $this->minutes);
        }
        return $response;
    }
}
