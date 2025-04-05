<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Products\Repositories\ProductRepository::class,
            \App\Infrastructure\Products\Repositories\EloquentProductRepository::class
        );

        $this->app->bind(
            \App\Domain\Products\Services\ExternalProductService::class,
            \App\Infrastructure\Services\FakeStoreApiService::class
        );

        $this->app->bind(
            \App\Domain\Products\Events\DomainEventDispatcher::class,
            \App\Infrastructure\Events\LaravelDomainEventDispatcher::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
