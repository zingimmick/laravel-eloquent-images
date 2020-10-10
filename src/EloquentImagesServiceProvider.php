<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentImages;

use Illuminate\Support\ServiceProvider;

class EloquentImagesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    $this->getConfigPath() => config_path('eloquent-images.php'),
                ],
                'config'
            );
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'eloquent-images');
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        }
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/eloquent-images.php';
    }
}
