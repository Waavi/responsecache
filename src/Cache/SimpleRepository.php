<?php

namespace Waavi\ResponseCache\Cache;

use Illuminate\Contracts\Cache\Store;

class SimpleRepository implements CacheRepositoryInterface
{
    /**
     * The cache store implementation.
     *
     * @var \Illuminate\Contracts\Cache\Store
     */
    protected $store;

    /**
     * Create a new cache repository instance.
     *
     * @param  \Illuminate\Contracts\Cache\Store  $store
     * @return void
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     *  Checks if an entry with the given key exists in the cache.
     *
     *  @param  string  $key
     *  @return boolean
     */
    public function has($key)
    {
        return !is_null($this->get($key));
    }

    /**
     *  Get an item from the cache
     *
     *  @param  string  $key
     *  @return mixed
     */
    public function get($key)
    {
        return $this->store->get($key);
    }

    /**
     *  Put an item into the cache store
     *
     *  @param  string  $key
     *  @param  mixed   $content
     *  @param  integer $minutes
     *  @return void
     */
    public function put($key, $content, $minutes)
    {
        $this->store->put($key, $content, $minutes);
    }

    /**
     *  Clear the cache
     *
     *  @return void
     */
    public function clear()
    {
        $this->store->flush();
    }
}
