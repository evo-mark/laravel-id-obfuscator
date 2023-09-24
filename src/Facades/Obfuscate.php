<?php

namespace EvoMark\LarvelIdObfuscator\Facades;

use EvoMark\LarvelIdObfuscator\Services\ObfuscateService;
use Illuminate\Support\Facades\Facade;

class Obfuscate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ObfuscateService::class;
    }
}
