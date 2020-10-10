<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentImages\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Zing\LaravelEloquentImages\HasImages;

/**
 * @method static \Zing\LaravelEloquentImages\Tests\Models\Product|\Illuminate\Database\Eloquent\Builder query()
 */
class Product extends Model
{
    use HasImages;
}
