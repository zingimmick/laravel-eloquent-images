<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentImages\Tests;

class ServiceProviderTest extends TestCase
{
    public function testConfig(): void
    {
        self::assertIsArray(config('eloquent-images'));
    }
}
