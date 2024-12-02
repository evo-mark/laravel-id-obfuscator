<p align="center">
    <a href="https://evomark.co.uk" target="_blank" alt="Link to evoMark's website">
        <picture>
          <source media="(prefers-color-scheme: dark)" srcset="https://evomark.co.uk/wp-content/uploads/static/evomark-logo--dark.svg">
          <source media="(prefers-color-scheme: light)" srcset="https://evomark.co.uk/wp-content/uploads/static/evomark-logo--light.svg">
          <img alt="evoMark company logo" src="https://evomark.co.uk/wp-content/uploads/static/evomark-logo--light.svg" width="500">
        </picture>
    </a>
</p>

<p align="center">
    <a href="https://packagist.org/packages/evo-mark/laravel-id-obfuscator"><img src="https://img.shields.io/packagist/v/evo-mark/laravel-id-obfuscator?logo=packagist&logoColor=white" alt="Build status" /></a>
    <a href="https://packagist.org/packages/evo-mark/laravel-id-obfuscator"><img src="https://img.shields.io/packagist/dt/evo-mark/laravel-id-obfuscator" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/evo-mark/laravel-id-obfuscator"><img src="https://img.shields.io/packagist/l/evo-mark/laravel-id-obfuscator" alt="Licence"></a>
</p>
<br />

# Laravel ID Obfuscator

Incrementing primary keys may reveal more than you wish in a public-facing application. Order IDs can reveal your sales volume to competitors and User IDs can invite enumeration attacks.

This package implements a two-way hashing on `Obfuscatable` models and converts an ID of, say, `7` into an ID of `fh38aj2e` when it travels to the frontend and converts it back on return.

> Warning: This package only obfuscates IDs and should not be used if secure encryption of identifiers is required

## Installation

```bash
composer require evo-mark/laravel-id-obfuscator
```

## Models

### Usage

```php
use EvoMark\LaravelIdObfuscator\Traits\Obfuscatable;

class User extends Authenticatable
{
    use Obfuscatable;
}
```

Using the `Obfuscatable` trait provides automatic route model binding with decoding and then automatic encoding when the primary key is sent to the frontend

```php
Route::get('/users/{user}', [SomeController::class, 'index']);

// SomeController

public function index(User $user)
{
    // $user will now have the decoded ID ready for internal use

    // If you need to access the obfuscated ID internally, you can use
    $obfuscatedId = $user->obfuscatedId;
}
```

`Obfuscatable` models will also feature automatic decoding when using the model's `find`-style functions: e.g. `find`, `findOrFail`, `findMany`, `findOrNew`, `findOr`

```php
// SomeController

/**
 * @param string $id The obfuscated order ID
 */
public function index($id)
{
    $order = Order::find($id);
}
```

## Validation

**Laravel ID Obfuscator** comes with a built-in rule extension for validating incoming obfuscated ids, simply:

```php
public function store($request)
{
    $validated = $request->validate([
        'id' => ['required','id_exists:users']
    ]);
}
```

## Facade

You can access the encoding and decoding features anytime via the provided facade.

```php
use EvoMark\LaravelIdObfuscator\Facades\Obfuscate;

$encoded = Obfuscate::encode(5);
$decoded = Obfuscate::decode($encoded);
```

## Config

You can publish the package config by running the following Artisan command:

```bash
php artisan v:p --provider="EvoMark\LaravelIdObfuscator\Provider"
```

| Setting  | Type   | Default                 | Description                              |
| -------- | ------ | ----------------------- | ---------------------------------------- |
| seed     | string | laravel-id-obfuscator   | A seed string for the encoder            |
| length   | int    | 8                       | The amount of chars to pad the output to |
| alphabet | string | [a-zA-Z0-9] (as string) | The alphabet to use when encoding IDs    |

## Q & A

1. Why not use UUIDs?

- UUIDs can be [Bad for database performance](https://www.danielfullstack.com/article/stop-using-uuids-in-your-database), whereas this obfuscation only runs when data bridges between the backend and the frontend of your application.

## Limitations

- Laravel ID Obfuscator can only be used on incrementing primary keys
- Since this package overrides the `newEloquentBuilder` method on obfuscated models, it is incompatible with any other packages that also do the same. Some examples might include:
    - [mikebronner/laravel-model-caching](https://github.com/mikebronner/laravel-model-caching)
    - [grimzy/laravel-mysql-spatial](https://github.com/grimzy/laravel-mysql-spatial)
    - [fico7489/laravel-pivot](https://github.com/fico7489/laravel-pivot)
    - [spatie/laravel-query-builder](https://github.com/spatie/laravel-query-builder)
    - [dwightwatson/rememberable](https://github.com/dwightwatson/rememberable)
    - [chelout/laravel-relationship-events](https://github.com/chelout/laravel-relationship-events)
    - [lazychaser/laravel-nestedset](https://github.com/lazychaser/laravel-nestedset)
- Presently, if an `Obfuscatable` model appears as part of another model as a foreign key, it will not be obfuscated