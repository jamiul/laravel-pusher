<?php

namespace App\Providers;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ProductRepositoryInterface::class, function ($app) {
            return new ProductRepository(new Product());
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 