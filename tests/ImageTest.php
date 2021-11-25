<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentImages\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Zing\LaravelEloquentImages\Image;

class ImageTest extends TestCase
{
    use WithFaker;

    public function testFillable(): void
    {
        $name = $this->faker->name();
        Image::query()->create([
            'url' => $name,
        ]);
        $this->assertDatabaseHas(Image::query()->getModel()->getTable(), [
            'url' => $name,
        ]);
    }
}
