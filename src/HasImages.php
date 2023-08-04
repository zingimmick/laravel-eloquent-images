<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentImages;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\Zing\LaravelEloquentImages\Image[] $images
 * @property-read int|null $images_count
 *
 * @method static static|\Illuminate\Database\Eloquent\Builder withAllImages($images)
 * @method static static|\Illuminate\Database\Eloquent\Builder withAnyImages($images)
 */
trait HasImages
{
    /**
     * @return class-string<\Zing\LaravelEloquentImages\Image>
     */
    protected static function getImageClassName(): string
    {
        return config('eloquent-images.models.image');
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(
            static::getImageClassName(),
            'imageable',
            config('eloquent-images.table_names.model_has_images'),
            config('eloquent-images.column_names.imageable_morph_key'),
            'image_id'
        )
            ->orderBy('priority');
    }

    /**
     * @param iterable<int, \Zing\LaravelEloquentImages\Image|string>|\Illuminate\Contracts\Support\Arrayable<int, \Zing\LaravelEloquentImages\Image|string>|\Zing\LaravelEloquentImages\Image $images
     */
    public function scopeWithAllImages(
        Builder $query,
        \Illuminate\Contracts\Support\Arrayable|Image|iterable $images
    ): Builder {
        $images = static::parseImages($images);
        $images->each(
            static function (Model $image) use ($query): void {
                $query->whereHas(
                    'images',
                    static function (Builder $query) use ($image): void {
                        $query->whereKey($image->getKey());
                    }
                );
            }
        );

        return $query;
    }

    /**
     * @param iterable<int, \Zing\LaravelEloquentImages\Image|string>|\Illuminate\Contracts\Support\Arrayable<int, \Zing\LaravelEloquentImages\Image|string>|\Zing\LaravelEloquentImages\Image $images
     */
    public function scopeWithAnyImages(
        Builder $query,
        \Illuminate\Contracts\Support\Arrayable|Image|iterable $images
    ): Builder {
        $images = static::parseImages($images);

        return $query->whereHas(
            'images',
            static function (Builder $query) use ($images): void {
                $query->whereKey($images->modelKeys());
            }
        );
    }

    /**
     * @param iterable<int, \Zing\LaravelEloquentImages\Image|string>|\Illuminate\Contracts\Support\Arrayable<int, \Zing\LaravelEloquentImages\Image|string>|\Zing\LaravelEloquentImages\Image $images
     *
     * @return $this
     */
    public function attachImages(\Illuminate\Contracts\Support\Arrayable|Image|iterable $images)
    {
        $this->images()
            ->attach(static::parseImages($images));

        return $this;
    }

    /**
     * @return $this
     */
    public function attachImage(Image|string $image)
    {
        $this->attachImages([$image]);

        return $this;
    }

    /**
     * @param iterable<int, \Zing\LaravelEloquentImages\Image|string>|\Illuminate\Contracts\Support\Arrayable<int, \Zing\LaravelEloquentImages\Image|string> $images
     *
     * @return $this
     */
    public function detachImages(\Illuminate\Contracts\Support\Arrayable|iterable $images)
    {
        $this->images()
            ->detach(static::parseImages($images));

        return $this;
    }

    /**
     * @return $this
     */
    public function detachImage(Image|string $image)
    {
        $this->detachImages([$image]);

        return $this;
    }

    /**
     * @param iterable<int, \Zing\LaravelEloquentImages\Image|string>|\Illuminate\Contracts\Support\Arrayable<int, \Zing\LaravelEloquentImages\Image|string> $images
     *
     * @return $this
     */
    public function syncImages(\Illuminate\Contracts\Support\Arrayable|iterable $images)
    {
        $this->images()
            ->sync(static::parseImages($images)->mapWithKeys(
                static fn ($image, $key): array => [
                    $image->getKey() => [
                        'priority' => $key,
                    ],
                ]
            )->toArray());

        return $this;
    }

    /**
     * @param iterable<int, \Zing\LaravelEloquentImages\Image|string>|\Illuminate\Contracts\Support\Arrayable<int, \Zing\LaravelEloquentImages\Image|string> $values
     */
    protected static function parseImages(\Illuminate\Contracts\Support\Arrayable|iterable $values): Collection
    {
        return Collection::make($values)->map(static fn ($value): Model => self::parseImage($value));
    }

    /**
     * @return \Zing\LaravelEloquentImages\Image
     */
    protected static function parseImage(Model|string $value): Model
    {
        if (\is_string($value)) {
            return forward_static_call([static::getImageClassName(), 'query'])->firstOrCreate([
                'url' => $value,
            ]);
        }

        if (is_a($value, self::getImageClassName(), false)) {
            return $value;
        }

        throw new \RuntimeException('Unsupported type for image');
    }
}
