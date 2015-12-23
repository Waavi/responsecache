<?php

namespace Waavi\ResponseCache\Parsers;

use Illuminate\Http\Response;

class ResponseParser
{
    /**
     *  Http Response
     *
     *  @var string
     */
    protected $response;

    /**
     *  Create new ResponseParser instance
     *
     *  @param  string  $response
     *  @return void
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     *  Check if this response is cacheable
     *
     *  @return boolean
     */
    public function isCacheable()
    {
        return strlen($this->response->getContent()) > 0 && ($this->response->isSuccessful() || $this->response->isRedirection());
    }

    /**
     *  Return the Cache value to store to represent this response
     *
     *  @param  string  $headerName     Name of the cache header. Null if none is to be included
     *  @return Response|null
     */
    public function cacheValue($headerName = null)
    {
        if (!$this->isCacheable()) {
            return null;
        }

        if ($headerName) {
            $this->response = clone $this->response;
            $response->headers->set($headerName, 'cached on ' . date('Y-m-d H:i:s'));
        }
        $content    = $this->response->getContent();
        $statusCode = $this->response->getStatusCode();
        $headers    = $this->response->headers;

        return serialize(compact('content', 'statusCode', 'headers'));
    }
}
