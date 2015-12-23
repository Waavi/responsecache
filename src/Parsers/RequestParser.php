<?php

namespace Waavi\ResponseCache\Parsers;

use Illuminate\Http\Request;

class RequestParser
{
    /**
     *  HTTP Request
     *
     *  @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     *  Build a new Request Parser instance
     *
     *  @param \Illuminate\Http\Request $request
     *  @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     *  Check if the request is Cacheable, meaning there is no logged in user and it's a GET request
     *
     *  @return boolean
     */
    public function isCacheable()
    {
        return is_null($this->request->user()) && $this->request->isMethod('GET') && !$this->request->ajax();
    }

    /**
     *  Return the cache key for this request
     *
     *  @return boolean
     */
    public function cacheKey()
    {
        return $this->isCacheable() ? md5($this->request->getUri()) : null;
    }
}
