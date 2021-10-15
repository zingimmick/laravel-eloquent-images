<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentImages\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class CustomImage extends Model
{
    public function getTable()
    {
        return config('eloquent-images.table_names.images', parent::getTable());
    }

    /**
     * @var string[]
     */
    protected $fillable = ['url'];
}
