<?php namespace Waavi\ResponseCache\Cache;

use Illuminate\Contracts\Cache\Store;

class TaggedRepository implements CacheRepositoryInterface
{
    /**
     * The cache store implementation.
     *
     * @var \Illuminate\Contracts\Cache\Store
     */
    protected $store;

    /**
     * The translation cache tag
     *
     * @var string
     */
    protected $cacheTag;

    /**
     * Create a new cache repository instance.
     *
     * @param  \Illuminate\Contracts\Cache\Store  $store
     * @return void
     */
    public function __construct(Store $store, $cacheTag)
    {
        $this->store    = $store;
        $this->cacheTag = $cacheTag;
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
        return $this->store->tags([$this->cacheTag, $key])->get($key);
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
        $this->store->tags([$this->cacheTag, $key])->put($key, $content, $minutes);
    }

    /**
     *  Completely clear the cache
     *
     *  @return void
     */
    public function clear()
    {
        $this->store->tags([$this->cacheTag])->flush();
    }
}
