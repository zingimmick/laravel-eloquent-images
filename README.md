# Laravel Eloquent Images
<p align="center">
<a href="https://github.com/zingimmick/laravel-eloquent-images/actions"><img src="https://github.com/zingimmick/laravel-eloquent-images/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://codecov.io/gh/zingimmick/laravel-eloquent-images"><img src="https://codecov.io/gh/zingimmick/laravel-eloquent-images/branch/main/graph/badge.svg" alt="Code Coverage" /></a>
<a href="https://packagist.org/packages/zing/laravel-eloquent-images"><img src="https://poser.pugx.org/zing/laravel-eloquent-images/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-eloquent-images"><img src="https://poser.pugx.org/zing/laravel-eloquent-images/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/zing/laravel-eloquent-images"><img src="https://poser.pugx.org/zing/laravel-eloquent-images/v/unstable.svg" alt="Latest Unstable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-eloquent-images"><img src="https://poser.pugx.org/zing/laravel-eloquent-images/license" alt="License"></a>
<a href="https://codeclimate.com/github/zingimmick/laravel-eloquent-images/maintainability"><img src="https://api.codeclimate.com/v1/badges/3d8276d89c4f346eddd5/maintainability" alt="Code Climate" /></a>
</p>

> **Requires [PHP 7.2.0+](https://php.net/releases/)**

Require Laravel Eloquent Images using [Composer](https://getcomposer.org):

```bash
composer require zing/laravel-eloquent-images
```
## Usage

```php
use Zing\LaravelEloquentImages\Tests\Models\Product;
use Zing\LaravelEloquentImages\Image;

$product = Product::query()->first();
// Add image(s) to model
$product->attachImage("https://github.com/zingimmick/laravel-eloquent-images/workflows/tests/badge.svg");
$product->attachImages([
    "https://github.com/zingimmick/laravel-eloquent-images/workflows/tests/badge.svg",
    Image::query()->first()
]);
// Remove image(s) from model
$product->detachImage("https://github.com/zingimmick/laravel-eloquent-images/workflows/tests/badge.svg");
$product->detachImages([
    "https://github.com/zingimmick/laravel-eloquent-images/workflows/tests/badge.svg",
    Image::query()->first()
]);
// Reset images of model
$product->syncImages([
    "https://github.com/zingimmick/laravel-eloquent-images/workflows/tests/badge.svg",
    Image::query()->first()
]);
// Get images of model
$product->images;
// Eager load images
$products = Product::query()->with('images')->withCount('images')->get();
$products->each(function (Product $product){
    $product->images->dump();
    $product->images_count;
});
```

## License

Laravel Eloquent Images is an open-sourced software licensed under the [MIT license](LICENSE).
