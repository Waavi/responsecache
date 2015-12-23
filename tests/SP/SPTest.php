<?php

namespace Waavi\ResponseCache\Test;

use Waavi\ResponseCache\ResponseCache;
use Waavi\ResponseCache\Test\TestCase;

class SPTest extends TestCase
{
    /**
     *  @test
     */
    public function it_loads_the_config_file()
    {
        $this->assertTrue(config('responsecache.enabled'));
        $this->assertEquals(60 * 24, config('responsecache.timeout'));
        $this->assertEquals('laravel-response-cache', config('responsecache.headerName'));
        $this->assertTrue(config('responsecache.withHeaders'));
    }

    /**
     *  @test
     */
    public function it_loads_the_facade()
    {
        $this->assertInstanceOf(ResponseCache::class, \App::make('responsecache'));
    }
}
