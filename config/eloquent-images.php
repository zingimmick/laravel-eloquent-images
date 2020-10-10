<?php

declare(strict_types=1);

use Zing\LaravelEloquentImages\Image;

return [
    'models' => [
        'image' => Image::class,
    ],
    'table_names' => [
        'images' => 'images',
        'model_has_images' => 'imagegables',
    ],
    'column_names' => [
        'imagegable_morph_key' => 'imagegable_id',
    ],
];
