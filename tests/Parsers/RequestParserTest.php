<?php

namespace Waavi\ResponseCache\Test\Parsers;

use Illuminate\Http\Request;
use Waavi\ResponseCache\Parsers\RequestParser;
use Waavi\ResponseCache\Test\TestCase;

class RequestParserTest extends TestCase
{
    /**
     *  @test
     */
    public function it_parses_requests()
    {
        $request       = Request::create('/', 'GET', ['name' => 'Taylor', 'age' => 26]);
        $requestParser = new RequestParser($request);
        $this->assertTrue($requestParser->isCacheable());
        $this->assertEquals(md5('http://localhost/?age=26&name=Taylor'), $requestParser->cacheKey());
    }

    /**
     *  @test
     */
    public function cannot_parse_POST_requests()
    {
        $request       = Request::create('/', 'POST', ['name' => 'Taylor', 'age' => 26]);
        $requestParser = new RequestParser($request);
        $this->assertFalse($requestParser->isCacheable());
        $this->assertNull($requestParser->cacheKey());
    }

    /**
     *  @test
     */
    public function cannot_parse_PUT_requests()
    {
        $request       = Request::create('/', 'PUT', ['name' => 'Taylor', 'age' => 26]);
        $requestParser = new RequestParser($request);
        $this->assertFalse($requestParser->isCacheable());
        $this->assertNull($requestParser->cacheKey());
    }

    /**
     *  @test
     */
    public function cannot_parse_DELETE_requests()
    {
        $request       = Request::create('/', 'DELETE', ['name' => 'Taylor', 'age' => 26]);
        $requestParser = new RequestParser($request);
        $this->assertFalse($requestParser->isCacheable());
        $this->assertNull($requestParser->cacheKey());
    }

    /**
     *  @test
     */
    public function cannot_parse_ajax_requests()
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'age' => 26]);
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $requestParser = new RequestParser($request);
        $this->assertFalse($requestParser->isCacheable());
        $this->assertNull($requestParser->cacheKey());
    }

    /**
     *  @test
     */
    public function cannot_parse_logged_users_requests()
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'age' => 26]);
        $request->setUserResolver(function () {return true;});
        $requestParser = new RequestParser($request);
        $this->assertFalse($requestParser->isCacheable());
        $this->assertNull($requestParser->cacheKey());
    }
}
