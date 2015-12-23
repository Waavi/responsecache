<?php

return [
    // Whether the cache is active (boolean)
    'enabled'     => env('RESPONSE_CACHE_ACTIVE', true),

    // Default cache timeout in minutes (1 day default)
    'timeout'     => env('RESPONSE_CACHE_TIMEOUT', 60 * 24),

    // Cache HTTP header
    'headerName'  => 'laravel-response-cache',

    'withHeaders' => true,

    // Cache tag to use if a taggable cache store is used
    'cache-tag'   => 'waavi-response-cache',
];
