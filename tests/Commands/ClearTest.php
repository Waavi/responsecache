<?php namespace Waavi\ResponseCache\Test\Commands;

use Mockery;
use Waavi\ResponseCache\Cache\RepositoryInterface;
use Waavi\ResponseCache\Test\TestCase;

class FlushTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repository = \App::make(RepositoryInterface::class);
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    /**
     * @test
     */
    public function it_does_nothing_if_cache_disabled()
    {
        $this->repository->put('key', 'value', 60);
        $this->assertTrue($this->repository->has('key'));
        $this->app['config']->set('responsecache.enabled', false);
        $command = Mockery::mock('Waavi\ResponseCache\Commands\CacheClearCommand[info]', [$this->repository, $this->app['config']]);
        $command->shouldReceive('info')->with('The response cache is disabled.')->once();
        $command->fire();
        $this->assertTrue($this->repository->has('key'));
    }

    /**
     * @test
     */
    public function it_flushes_the_cache()
    {
        $this->repository->put('key', 'value', 60);
        $this->assertTrue($this->repository->has('key'));
        $command = Mockery::mock('Waavi\ResponseCache\Commands\CacheClearCommand[info]', [$this->repository, $this->app['config']]);
        $command->shouldReceive('info')->with('Response cache cleared.')->once();
        $command->fire();
        $this->assertFalse($this->repository->has('key'));
    }
}
