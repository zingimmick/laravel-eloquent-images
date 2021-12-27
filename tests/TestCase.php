<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentImages\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Zing\LaravelEloquentImages\EloquentImagesServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * @before
     */
    protected function setUpDatabaseMigrations(): void
    {
        $this->afterApplicationCreated(function (): void {
            $this->loadMigrationsFrom(__DIR__ . '/../migrations');
            Schema::create(
                'products',
                function (Blueprint $table): void {
                    $table->bigIncrements('id');
                    $table->timestamps();
                }
            );
        });
    }

    protected function getEnvironmentSetUp($app): void
    {
        config([
            'database.default' => 'testing',
        ]);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array<class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [EloquentImagesServiceProvider::class];
    }
}
