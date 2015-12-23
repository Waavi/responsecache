<?php namespace Waavi\ResponseCache\Test\Cache;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\FileStore;
use Waavi\ResponseCache\Cache\RepositoryFactory;
use Waavi\ResponseCache\Cache\SimpleRepository;
use Waavi\ResponseCache\Cache\TaggedRepository;
use Waavi\ResponseCache\Test\TestCase;

class RepositoryFactoryTest extends TestCase
{
    public function setUp()
    {
        // During the parent's setup, both a 'es' 'Spanish' and 'en' 'English' languages are inserted into the database.
        parent::setUp();
    }

    /**
     * @test
     */
    public function test_returns_simple_cache_if_non_taggable_store()
    {
        $store = new FileStore(\App::make('files'), __DIR__ . '/temp');
        $repo  = RepositoryFactory::make($store, 'translation');
        $this->assertInstanceOf(SimpleRepository::class, $repo);
    }

    /**
     * @test
     */
    public function test_returns_simple_cache_if_taggable_store()
    {
        $store = new ArrayStore;
        $repo  = RepositoryFactory::make($store, 'translation');
        $this->assertInstanceOf(TaggedRepository::class, $repo);
    }
}
