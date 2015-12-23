<?php namespace Waavi\ResponseCache;

use Illuminate\Support\ServiceProvider;

class ResponseCacheServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/responsecache.php' => config_path('responsecache.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__ . '/../config/responsecache.php', 'responsecache'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('responsecache', ResponseCache::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['responsecache'];
    }

}
