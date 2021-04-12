<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentImages\Tests;

use Illuminate\Database\Eloquent\Collection;
use Zing\LaravelEloquentImages\Image;
use Zing\LaravelEloquentImages\Tests\Models\Product;

class HasImagesTest extends TestCase
{
    protected function getImageClassName()
    {
        return Image::class;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::query()->create();
    }

    protected $product;

    public function testDetachImages(): void
    {
        $this->product->attachImages(['foo', 'bar']);
        $this->product->detachImages(['foo']);
        self::assertSame(1, $this->product->images()->whereIn('url', ['foo', 'bar'])->count());
    }

    public function testAttachImages(): void
    {
        $this->product->attachImages(['foo', 'bar']);
        self::assertSame(2, $this->product->images()->whereIn('url', ['foo', 'bar'])->count());
    }

    public function testImages(): void
    {
        $this->product->attachImages(['foo', 'bar']);
        self::assertInstanceOf($this->getImageClassName(), $this->product->images()->first());
        self::assertInstanceOf(Collection::class, $this->product->images()->get());
    }

    public function testImagesPriority(): void
    {
        $this->product->syncImages(['foo', 'bar']);
        self::assertSame(['foo', 'bar'], $this->product->images()->pluck('url')->toArray());
        $this->product->syncImages(['bar', 'foo']);
        self::assertSame(['bar', 'foo'], $this->product->images()->pluck('url')->toArray());
    }

    public function testAttachImage(): void
    {
        $this->product->attachImage('foo');
        self::assertSame(1, $this->product->images()->whereIn('url', ['foo', 'bar'])->count());
    }

    public function testDetachImage(): void
    {
        $this->product->attachImages(['foo', 'bar']);
        $this->product->detachImage('foo');
        self::assertSame(1, $this->product->images()->whereIn('url', ['foo', 'bar'])->count());
    }

    public function testScopeWithAllImages(): void
    {
        $this->product->attachImage('foo');
        self::assertFalse(Product::query()->withAllImages(['foo', 'bar'])->exists());
        self::assertTrue(Product::query()->withAllImages(['foo'])->exists());
    }

    public function testSyncImages(): void
    {
        $this->product->attachImages(['foo', 'bar']);
        $this->product->syncImages([$this->product->images()->first()]);
        self::assertSame(1, $this->product->images()->count());
        $this->product->syncImages([]);
        self::assertSame(0, $this->product->images()->count());
    }

    public function testScopeWithAnyImages(): void
    {
        $this->product->attachImage('foo');
        self::assertTrue(Product::query()->withAnyImages(['foo', 'bar'])->exists());
    }
}
