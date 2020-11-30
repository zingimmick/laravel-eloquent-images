<?php

declare(strict_types=1);

use Zing\LaravelEloquentImages\Image;

return [
    'load_migrations' => true,
    'models' => [
        'image' => Image::class,
    ],
    'table_names' => [
        'images' => 'images',
        'model_has_images' => 'imageables',
    ],
    'column_names' => [
        'imageable_morph_key' => 'imageable_id',
    ],
];
