<?php
namespace Waavi\ResponseCache\Test;

use ResponseCache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IntegrationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    /**
     * @test
     */
    public function it_will_cache_a_get_request()
    {
        $firstResponse  = $this->call('GET', '/random');
        $secondResponse = $this->call('GET', '/random');
        $this->assertRegularResponse($firstResponse);
        $this->assertCachedResponse($secondResponse);
        $this->assertSameResponse($firstResponse, $secondResponse);
    }

    /**
     * @test
     */
    public function it_will_not_cache_redirects()
    {
        $firstResponse  = $this->call('GET', '/redirect');
        $secondResponse = $this->call('GET', '/redirect');
        $this->assertRegularResponse($firstResponse);
        $this->assertRegularResponse($secondResponse);
    }

    /**
     * @test
     */
    public function it_will_not_cache_empty_responses()
    {
        $firstResponse  = $this->call('GET', '/empty');
        $secondResponse = $this->call('GET', '/empty');
        $this->assertRegularResponse($firstResponse);
        $this->assertRegularResponse($secondResponse);
    }

    /**
     * @test
     */
    public function it_will_not_cache_errors()
    {
        $this->setExpectedException(NotFoundHttpException::class);
        $firstResponse  = $this->call('GET', '/notfound');
        $secondResponse = $this->call('GET', '/notfound');
        $this->assertRegularResponse($firstResponse);
        $this->assertRegularResponse($secondResponse);
    }
    /**
     * @test
     */
    public function it_will_not_cache_a_post_request()
    {
        $firstResponse  = $this->call('POST', '/random');
        $secondResponse = $this->call('POST', '/random');
        $this->assertRegularResponse($firstResponse);
        $this->assertRegularResponse($secondResponse);
        $this->assertDifferentResponse($firstResponse, $secondResponse);
    }
    /**
     * @test
     */
    public function it_can_flush_the_cached_requests()
    {
        $firstResponse = $this->call('GET', '/random');
        $this->assertRegularResponse($firstResponse);
        ResponseCache::clear();
        $secondResponse = $this->call('GET', '/random');
        $this->assertRegularResponse($secondResponse);
        $this->assertDifferentResponse($firstResponse, $secondResponse);
    }
    /**
     * @test
     */
    public function it_will_not_cache_responses_for_logged_in_users()
    {
        $this->call('GET', '/login/1');
        $firstUserFirstCall  = $this->call('GET', '/random');
        $firstUserSecondCall = $this->call('GET', '/random');
        $this->assertRegularResponse($firstUserFirstCall);
        $this->assertRegularResponse($firstUserSecondCall);
        $this->assertDifferentResponse($firstUserFirstCall, $firstUserSecondCall);
    }
    /**
     * @test
     */
    public function it_will_not_cache_routes_with_no_middleware()
    {
        $firstResponse  = $this->call('GET', '/no-cache');
        $secondResponse = $this->call('GET', '/no-cache');
        $this->assertRegularResponse($firstResponse);
        $this->assertRegularResponse($secondResponse);
        $this->assertDifferentResponse($firstResponse, $secondResponse);
    }
    /**
     * @test
     */
    public function it_will_not_cache_request_when_the_package_is_not_enabled()
    {
        $this->app['config']->set('responsecache.enabled', false);
        $firstResponse  = $this->call('GET', '/random');
        $secondResponse = $this->call('GET', '/random');
        $this->assertRegularResponse($firstResponse);
        $this->assertRegularResponse($secondResponse);
        $this->assertDifferentResponse($firstResponse, $secondResponse);
    }
    /**
     * @test
     */
    public function it_will_not_serve_cached_requests_when_it_is_disabled_in_the_config_file()
    {
        $firstResponse = $this->call('GET', '/random');
        $this->app['config']->set('responsecache.enabled', false);
        $secondResponse = $this->call('GET', '/random');
        $this->assertRegularResponse($firstResponse);
        $this->assertRegularResponse($secondResponse);
        $this->assertDifferentResponse($firstResponse, $secondResponse);
    }
}
