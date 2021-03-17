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
    protected static function getImageClassName()
    {
        return config('eloquent-images.models.image');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function images(): MorphToMany
    {
        return $this->morphToMany(static::getImageClassName(), 'imageable', config('eloquent-images.table_names.model_has_images'), config('eloquent-images.column_names.imageable_morph_key'), 'image_id')
            ->orderBy('priority');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|\ArrayAccess|\Zing\LaravelEloquentImages\Image $images
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllImages(Builder $query, $images): Builder
    {
        $images = static::parseImages($images);
        $images->each(
            function (Model $image) use ($query): void {
                $query->whereHas(
                    'images',
                    function (Builder $query) use ($image): void {
                        $query->whereKey($image->getKey());
                    }
                );
            }
        );

        return $query;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|\ArrayAccess|\Zing\LaravelEloquentImages\Image $images
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyImages(Builder $query, $images): Builder
    {
        $images = static::parseImages($images);

        return $query->whereHas(
            'images',
            function (Builder $query) use ($images): void {
                $query->whereKey($images->modelKeys());
            }
        );
    }

    /**
     * @param array|\ArrayAccess|\Zing\LaravelEloquentImages\Image $images
     *
     * @return $this
     */
    public function attachImages($images)
    {
        $this->images()->attach(static::parseImages($images));

        return $this;
    }

    /**
     * @param string|\Zing\LaravelEloquentImages\Image $image
     *
     * @return $this
     */
    public function attachImage($image)
    {
        $this->attachImages([$image]);

        return $this;
    }

    /**
     * @param array|\ArrayAccess $images
     *
     * @return $this
     */
    public function detachImages($images)
    {
        $this->images()->detach(static::parseImages($images));

        return $this;
    }

    /**
     * @param string|\Zing\LaravelEloquentImages\Image $image
     *
     * @return $this
     */
    public function detachImage($image)
    {
        $this->detachImages([$image]);

        return $this;
    }

    /**
     * @param array|\ArrayAccess $images
     *
     * @return $this
     */
    public function syncImages($images)
    {
        $this->images()->sync(static::parseImages($images)->mapWithKeys(
            function ($image, $key) {
                return [
                    $image->getKey() => [
                        'priority' => $key,
                        
                    ], ];
            }
        )->toArray());

        return $this;
    }

    protected static function parseImages($values)
    {
        return Collection::make($values)->map(
            function ($value) {
                return self::parseImage($value);
            }
        );
    }

    protected static function parseImage($value): Model
    {
        if ($value instanceof Model) {
            return $value;
        }

        return forward_static_call([static::getImageClassName(), 'query'])->firstOrCreate(
            [
                'url' => $value,
            ]
        );
    }
}
