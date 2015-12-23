<?php

namespace Waavi\ResponseCache\Test;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Route;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();
        //$this->app['cache']->clear();
        $this->setUpDatabase($this->app);
        $this->setUpRoutes($this->app);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Waavi\ResponseCache\ResponseCacheServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'ResponseCache' => \Waavi\ResponseCache\Facades\ResponseCache::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('app.key', 'sF5r4kJy5HEcOEx3NWxUcYj1zLZLHxuu');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $this->artisan('migrate', ['--realpath' => realpath(__DIR__ . '/../database/migrations')]);

        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();
            $table->timestamps();
        });
        foreach (range(1, 10) as $index) {
            User::create(
                [
                    'name'     => "user{$index}",
                    'email'    => "user{$index}@waavi.com",
                    'password' => "password{$index}",
                ]
            );
        }
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpRoutes($app)
    {
        Route::any('/', ['middleware' => 'cache', function () {
            return 'home of ' . (auth()->check() ? auth()->user()->id : 'anonymous');
        }]);
        Route::any('/random', ['middleware' => 'cache', function () {
            return str_random();
        }]);
        Route::any('login/{id}', function ($id) {
            auth()->login(User::find($id));
            return redirect('/');
        });
        Route::any('empty', ['middleware' => 'cache', function () {
            return '';
        }]);
        Route::get('/no-cache', function () {
            return str_random();
        });
        Route::get('/redirect', ['middleware' => 'cache', function () {
            return redirect('/');
        }]);
    }

    protected function assertCachedResponse($response)
    {
        self::assertThat($response->headers->has('laravel-response-cache'), self::isTrue(), 'Failed to assert that the response has been cached');
    }
    protected function assertRegularResponse($response)
    {
        self::assertThat($response->headers->has('laravel-response-cache'), self::isFalse(), 'Failed to assert that the response was not cached');
    }
    protected function assertSameResponse($firstResponse, $secondResponse)
    {
        self::assertThat($firstResponse->getContent() == $secondResponse->getContent(), self::isTrue(), 'Failed to assert that two response are the same');
    }
    protected function assertDifferentResponse($firstResponse, $secondResponse)
    {
        self::assertThat($firstResponse->getContent() != $secondResponse->getContent(), self::isTrue(), 'Failed to assert that two response are the same');
    }
}
