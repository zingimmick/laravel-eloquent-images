<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentImages\Tests;

use Illuminate\Database\Eloquent\Collection;
use Zing\LaravelEloquentImages\Image;
use Zing\LaravelEloquentImages\Tests\Models\CustomImage;
use Zing\LaravelEloquentImages\Tests\Models\Product;

/**
 * @internal
 */
final class HasImagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::query()->create();
    }

    /**
     * @before
     */
    public function setUpImageClass(): void
    {
        $this->afterApplicationCreated(function (): void {
            $data = method_exists($this, 'providedData') ? $this->providedData() : $this->getProvidedData();
            if (isset($data[0])) {
                config([
                    'eloquent-images.models.image' => $data[0],
                ]);
            }
        });
    }

    /**
     * @return \Iterator<array{class-string<\Zing\LaravelEloquentImages\Image>}|array{class-string<\Zing\LaravelEloquentImages\Tests\Models\CustomImage>}>
     */
    public static function provideClasses(): \Iterator
    {
        yield [Image::class];

        yield [CustomImage::class];
    }

    private \Zing\LaravelEloquentImages\Tests\Models\Product $product;

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $imageClass
     */
    public function testDetachImages(string $imageClass): void
    {
        $this->product->attachImages(['foo', 'bar']);
        $this->product->detachImages(['foo']);
        self::assertSame(1, $this->product->images()->whereIn('url', ['foo', 'bar'])->count());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $imageClass
     */
    public function testAttachImages(string $imageClass): void
    {
        $this->product->attachImages(['foo', 'bar']);
        self::assertSame(2, $this->product->images()->whereIn('url', ['foo', 'bar'])->count());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $imageClass
     */
    public function testImages(string $imageClass): void
    {
        $this->product->attachImages(['foo', 'bar']);
        self::assertInstanceOf($imageClass, $this->product->images()->first());
        self::assertInstanceOf(Collection::class, $this->product->images()->get());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $imageClass
     */
    public function testImagesPriority(string $imageClass): void
    {
        $this->product->syncImages(['foo', 'bar']);
        self::assertSame(['foo', 'bar'], $this->product->images()->pluck('url')->toArray());
        $this->product->syncImages(['bar', 'foo']);
        self::assertSame(['bar', 'foo'], $this->product->images()->pluck('url')->toArray());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $imageClass
     */
    public function testAttachImage(string $imageClass): void
    {
        $this->product->attachImage('foo');
        self::assertSame(1, $this->product->images()->whereIn('url', ['foo', 'bar'])->count());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $imageClass
     */
    public function testDetachImage(string $imageClass): void
    {
        $this->product->attachImages(['foo', 'bar']);
        $this->product->detachImage('foo');
        self::assertSame(1, $this->product->images()->whereIn('url', ['foo', 'bar'])->count());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $imageClass
     */
    public function testScopeWithAllImages(string $imageClass): void
    {
        $this->product->attachImage('foo');
        self::assertFalse(Product::query()->withAllImages(['foo', 'bar'])->exists());
        self::assertTrue(Product::query()->withAllImages(['foo'])->exists());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $imageClass
     */
    public function testSyncImages(string $imageClass): void
    {
        $this->product->attachImages(['foo', 'bar']);
        $this->product->syncImages([$this->product->images()->firstOrFail()]);
        self::assertSame(1, $this->product->images()->count());
        $this->product->syncImages([]);
        self::assertSame(0, $this->product->images()->count());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $imageClass
     */
    public function testScopeWithAnyImages(string $imageClass): void
    {
        $this->product->attachImage('foo');
        self::assertTrue(Product::query()->withAnyImages(['foo', 'bar'])->exists());
    }
}
