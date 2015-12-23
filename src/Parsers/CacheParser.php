<?php

namespace Waavi\ResponseCache\Parsers;

use Illuminate\Http\Response;

class CacheParser
{
    /**
     *  Cache value
     *
     *  @var string
     */
    protected $cacheValue;

    /**
     *  Create new CacheParser instance
     *
     *  @param  string  $cacheValue
     *  @return void
     */
    public function __construct($cacheValue)
    {
        $this->cacheValue = $cacheValue;
    }

    /**
     *  Return the HTTP Response corresponding to this cache value
     *
     *  @return Response
     */
    public function response()
    {
        $properties        = unserialize($this->cacheValue);
        $response          = new Response($properties['content'], $properties['statusCode']);
        $response->headers = $properties['headers'];
        return $response;
    }
}
