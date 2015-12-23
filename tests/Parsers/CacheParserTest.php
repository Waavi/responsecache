<?php

namespace Waavi\ResponseCache\Test\Parsers;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Waavi\ResponseCache\Parsers\CacheParser;
use Waavi\ResponseCache\Test\TestCase;

class CacheParserTest extends TestCase
{
    /**
     *  @test
     */
    public function it_builds_a_response_from_a_cached_value()
    {
        $properties = [
            'content'    => 'My content',
            'statusCode' => 200,
            'headers'    => new ResponseHeaderBag(['header' => 'value']),
        ];
        $cacheParser = new CacheParser(serialize($properties));
        $response    = $cacheParser->response();

        $this->assertEquals($properties['content'], $response->getContent());
        $this->assertEquals($properties['statusCode'], $response->getStatusCode());
        $this->assertEquals('value', $response->headers->get('header'));
    }
}
