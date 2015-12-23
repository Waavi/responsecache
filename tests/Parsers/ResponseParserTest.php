<?php

namespace Waavi\ResponseCache\Test\Parsers;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Waavi\ResponseCache\Parsers\ResponseParser;
use Waavi\ResponseCache\Test\TestCase;

class ResponseParserTest extends TestCase
{
    /**
     *  @test
     */
    public function it_parses_responses()
    {
        $properties = [
            'content'    => 'My content',
            'statusCode' => 200,
            'headers'    => new ResponseHeaderBag(['header' => 'value']),
        ];
        $response = new Response;
        $response->setContent($properties['content']);
        $response->setStatusCode($properties['statusCode']);
        $response->headers = $properties['headers'];
        $responseParser    = new ResponseParser($response);
        $this->assertTrue($responseParser->isCacheable());
        $this->assertEquals(serialize($properties), $responseParser->cacheValue());
    }

    /**
     *  @test
     */
    public function it_doesnt_parse_empty_responses()
    {
        $response = new Response;
        $response->setContent('');
        $response->setStatusCode(200);
        $response->headers = new ResponseHeaderBag(['header' => 'value']);
        $responseParser    = new ResponseParser($response);
        $this->assertFalse($responseParser->isCacheable());
        $this->assertNull($responseParser->cacheValue());
    }

    /**
     *  @test
     */
    public function it_doesnt_parse_error_responses()
    {
        $response = new Response;
        $response->setContent('Content');
        $response->setStatusCode(500);
        $response->headers = new ResponseHeaderBag(['header' => 'value']);
        $responseParser    = new ResponseParser($response);
        $this->assertFalse($responseParser->isCacheable());
        $this->assertNull($responseParser->cacheValue());
    }
}
