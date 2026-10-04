<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Builder;
use App\Services\DocumentPostingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register DocumentPostingService as singleton
        $this->app->singleton(DocumentPostingService::class, function ($app) {
            return new DocumentPostingService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure database string length for SQLite compatibility
        Builder::defaultStringLength(191);
    }
}
