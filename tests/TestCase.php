<?php

namespace Waavi\ResponseCache\Test;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

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
        \Route::get('/', ['middleware' => 'cache', function () {
            return str_random();
        }]);
        \Route::post('/', ['middleware' => 'cache', function () {
            return 'Whoops';
        }]);
        \Route::get('/no-cache', function () {
            return str_random();
        });
        \Route::get('/redirect', function () {
            return redirect('/');
        });
    }

    protected function assertCachedResponse(Response $response)
    {
        self::assertThat($response->headers->has('laravel-reponsecache'), self::isTrue(), 'Failed to assert that the response has been cached');
    }
    protected function assertRegularResponse(Response $response)
    {
        self::assertThat($response->headers->has('laravel-reponsecache'), self::isFalse(), 'Failed to assert that the response was not cached');
    }
    protected function assertSameResponse(Response $firstResponse, Response $secondResponse)
    {
        self::assertThat($firstResponse->getContent() == $secondResponse->getContent(), self::isTrue(), 'Failed to assert that two response are the same');
    }
    protected function assertDifferentResponse(Response $firstResponse, Response $secondResponse)
    {
        self::assertThat($firstResponse->getContent() != $secondResponse->getContent(), self::isTrue(), 'Failed to assert that two response are the same');
    }
}
