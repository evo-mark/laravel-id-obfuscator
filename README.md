<a href="https://evomark.co.uk" target="_blank" alt="Link to evoMark's website" style="text-align:center;display:block">
    <picture>
      <source media="(prefers-color-scheme: dark)" srcset="https://evomark.co.uk/wp-content/uploads/static/evomark-logo--dark.svg">
      <source media="(prefers-color-scheme: light)" srcset="https://evomark.co.uk/wp-content/uploads/static/evomark-logo--light.svg">
      <img alt="evoMark company logo" src="https://evomark.co.uk/wp-content/uploads/static/evomark-logo--light.svg" style="max-height: 50px; max-width:100%">
    </picture>
</a>
<br />
<p align="center">
    <a href="https://packagist.org/packages/evo-mark/laravel-id-obfusactor"><img src="https://img.shields.io/packagist/v/evo-mark/laravel-id-obfusactor?logo=packagist&logoColor=white" alt="Build status" /></a>
    <a href="https://packagist.org/packages/evo-mark/laravel-id-obfusactor"><img src="https://img.shields.io/packagist/dt/evo-mark/laravel-id-obfusactor" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/evo-mark/laravel-id-obfusactor"><img src="https://img.shields.io/packagist/l/evo-mark/laravel-id-obfusactor" alt="Licence"></a>
</p>
<br />

# Laravel ID Obfuscator

**Laravel 10 Compatible**

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
