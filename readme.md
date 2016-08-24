# HTTP Response Cache for Laravel 5

[![Latest Version on Packagist](https://img.shields.io/packagist/v/waavi/responsecache.svg?style=flat-square)](https://packagist.org/packages/waavi/responsecache)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/Waavi/responsecache/master.svg?style=flat-square)](https://travis-ci.org/Waavi/responsecache)
[![Total Downloads](https://img.shields.io/packagist/dt/waavi/responsecache.svg?style=flat-square)](https://packagist.org/packages/waavi/responsecache)

Developed for Laravel 5.1 and based on [Spatie's Response cache](https://github.com/spatie/laravel-responsecache) this package allows you to cache successful GET Requests for non logged in users.

WAAVI is a web development studio based in Madrid, Spain. You can learn more about us at [waavi.com](http://waavi.com)

## Laravel compatibility

 Laravel  | translation
:---------|:----------
 5.1.x    | 1.0.x
 5.2.x    | 1.0.1 and up
 5.3.x    | 1.0.2 and up

## Installation

You may install the package via composer

    composer require waavi/responsecache 1.x

Add the service provider:

```php
// config/app.php

'providers' => [
    ...
    \Waavi\ResponseCache\ResponseCacheServiceProvider::class,
];
```

To enable the ResponseCache facade:

```php
// config/app.php

'aliases' => [
    ...
   'ResponseCache' => \Waavi\ResponseCache\Facades\ResponseCache::class,
];
```

Publish the config file

    php artisan vendor:publish --provider="Waavi\ResponseCache\ResponseCacheServiceProvider"

## Usage

### Cache middleware

You may now use the cache middleware in your routes to cache successful GET requests from non logged in users. By default responses a cached for 24 hours.

```php
// app/Http/routes.php

Route::get('/', ['middleware' => 'cache', 'uses' => 'HomeController@home']);
```

### Clearing the cache

You may clear the cache using the provided facade:

```php
\ResponseCache::clear();
```

Or through the provided artisan command

    php artisan responsecache:clear

In case your cache store allows for tags, then only the response cache will be cleared. Otherwise your whole app cache will be cleared.