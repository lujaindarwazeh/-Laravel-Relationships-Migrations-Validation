<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use Prometheus\Storage\Redis;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
{
    // $this->app->singleton(CollectorRegistry::class, function ($app) {
    //     $adapter = new Redis([
    //         'host' => '127.0.0.1',
    //         'port' => 6379,
    //     ]);
    //     return new CollectorRegistry($adapter);
    // });
}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
