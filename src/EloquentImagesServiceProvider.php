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
                'eloquent-images-config'
            );
            $this->publishes(
                [
                    $this->getMigrationsPath() => database_path('migrations'),
                ],
                'eloquent-images-migrations'
            );
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'eloquent-images');
        if (! $this->app->runningInConsole()) {
            return;
        }
        if (! $this->shouldLoadMigrations()) {
            return;
        }
        $this->loadMigrationsFrom($this->getMigrationsPath());
    }

    protected function getMigrationsPath(): string
    {
        return __DIR__ . '/../migrations';
    }

    private function shouldLoadMigrations(): bool
    {
        return (bool) config('eloquent-images.load_migrations');
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/eloquent-images.php';
    }
}
