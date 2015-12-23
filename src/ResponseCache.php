<?php

namespace Waavi\ResponseCache;

use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Waavi\ResponseCache\Cache\RepositoryInterface;
use Waavi\ResponseCache\Parsers\CacheParser;
use Waavi\ResponseCache\Parsers\RequestParser;
use Waavi\ResponseCache\Parsers\ResponseParser;

class ResponseCache
{
    /**
     *  Cache repository
     *
     *  @var \Waavi\ResponseCache\Cache\RepositoryInterface
     */
    protected $repository;

    /**
     *  Name of the header to add to cached responses
     *
     *  @var string
     */
    protected $headerName;

    /**
     *  Whether to allow queries or not
     *
     *  @var string
     */
    protected $allowQueries;

    public function __construct(RepositoryInterface $repository, Config $config)
    {
        $this->repository   = $repository;
        $this->headerName   = $config->get('responsecache.headerName', null);
        $this->allowQueries = $config->get('responsecache.enabled', false);
    }

    /**
     *  Return the cache repository
     *
     *  @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     *  Check if the cache has an entry for the given Request
     *
     *  @param  Request $request
     *  @return boolean
     */
    public function has(Request $request)
    {
        return !is_null($this->get($request));
    }

    /**
     *  Retrieve the cache entry for the given Request
     *
     *  @param  Request $request
     *  @return Response|null
     */
    public function get(Request $request)
    {
        if (!$this->allowQueries) {
            return null;
        }
        $parsedRequest = new RequestParser($request);
        $cacheKey      = $parsedRequest->cacheKey();
        // If the request is not cacheable, return null
        if (!$cacheKey) {
            return null;
        }
        $cacheValue = $this->repository->get($cacheKey);
        // If no response was found, return null
        if (!$cacheValue) {
            return null;
        }
        $parsedResponse = new CacheParser($cacheValue);
        return $parsedResponse->response();
    }

    /**
     *  Store the response for a given request in the cache
     *
     *  @param  Request     $request
     *  @param  Response    $response
     *  @param  integer     $minutes
     *  @return void
     */
    public function put(Request $request, Response $response, $minutes)
    {
        if (!$this->allowQueries) {
            return;
        }
        $parsedRequest = new RequestParser($request);
        $cacheKey      = $parsedRequest->cacheKey();
        // If the request is not cacheable, return
        if (!$cacheKey) {
            return;
        }

        $parsedResponse = new ResponseParser($response);
        $cacheValue     = $parsedResponse->cacheValue($this->headerName);
        // If the response is not cacheable, return
        if (!$cacheValue) {
            return;
        }

        $this->repository->put($cacheKey, $cacheValue, $minutes);
    }

    /**
     *  Clear the response cache.
     *
     *  @return void
     */
    public function clear()
    {
        $this->repository->clear();
    }
}
