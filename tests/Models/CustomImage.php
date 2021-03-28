<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentImages\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class CustomImage extends Model
{
    protected $table = 'images';

    protected $fillable = ['url'];
}
