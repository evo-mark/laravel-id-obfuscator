<?php

namespace EvoMark\LaravelIdObfuscator\Facades;

use EvoMark\LaravelIdObfuscator\Services\ObfuscateService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string encode(int|string $id)
 * @method static int|string|null (string $id)
 */
class Obfuscate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ObfuscateService::class;
    }
}
