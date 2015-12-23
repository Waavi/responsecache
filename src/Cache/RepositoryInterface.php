<?php

namespace Waavi\ResponseCache\Cache;

interface RepositoryInterface
{
    /**
     *  Checks if an entry with the given key exists in the cache.
     *
     *  @param  string  $key
     *  @return boolean
     */
    public function has($key);

    /**
     *  Get an item from the cache
     *
     *  @param  string $key
     *  @return mixed
     */
    public function get($key);

    /**
     *  Put an item into the cache store
     *
     *  @param  string  $key
     *  @param  mixed   $content
     *  @param  integer $minutes
     *  @return void
     */
    public function put($key, $content, $minutes);

    /**
     *  Clear the cache
     *
     *  @return void
     */
    public function clear();
}
