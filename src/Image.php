<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentImages;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Zing\LaravelEloquentImages\Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Zing\LaravelEloquentImages\Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Zing\LaravelEloquentImages\Image query()
 */
class Image extends Model
{
    public function getTable(): string
    {
        return config('eloquent-images.table_names.images', parent::getTable());
    }

    /**
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['url'];
}
