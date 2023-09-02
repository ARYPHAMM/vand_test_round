<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            \App\Infrastructure\Repositories\User\UserRepositoryInterface::class,
            \App\Infrastructure\Repositories\User\UserRepository::class
        );
        $this->app->singleton(
            \App\Infrastructure\Repositories\Store\StoreRepositoryInterface::class,
            \App\Infrastructure\Repositories\Store\StoreRepository::class
        );
        $this->app->singleton(
            \App\Infrastructure\Repositories\Product\ProductRepositoryInterface::class,
            \App\Infrastructure\Repositories\Product\ProductRepository::class
        );
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
