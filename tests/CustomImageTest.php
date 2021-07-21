<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentImages\Tests;

use Zing\LaravelEloquentImages\Tests\Models\CustomImage;

class CustomImageTest extends HasImagesTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        config([
            'eloquent-images.models.image' => $this->getImageClassName(),
        ]);
    }

    protected function getImageClassName(): string
    {
        return CustomImage::class;
    }
}
