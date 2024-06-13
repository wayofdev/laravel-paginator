<?php

declare(strict_types=1);

namespace WayOfDev\Paginator\Bridge\Laravel\Providers;

use Illuminate\Support\ServiceProvider;

final class PaginatorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../../../config/paginator.php' => config_path('paginator.php'),
            ], 'config');

            $this->registerConsoleCommands();
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../../../config/paginator.php',
            'paginator'
        );
    }

    private function registerConsoleCommands(): void
    {
        $this->commands([]);
    }
}
