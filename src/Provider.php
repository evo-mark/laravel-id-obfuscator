<?php

namespace EvoMark\LarvelIdObfuscator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use EvoMark\LarvelIdObfuscator\Facades\Obfuscate;
use EvoMark\LarvelIdObfuscator\Services\ObfuscateService;

class Provider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/obfuscator.php', 'obfuscator');
        $this->app->singleton(ObfuscateService::class, function () {
            return new ObfuscateService([
                'seed' => config('obfuscator.seed'),
                'length' => config('obfuscator.length'),
                'alphabet' => config('obfuscator.alphabet')
            ]);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/obfuscator.php' => config_path('obfuscator.php'),
        ], 'obfuscator');

        Validator::extend('id_exists', function ($attribute, $value, $parameters, $validator) {

            $decoded = Obfuscate::decode($value);
            if (empty($decoded)) {
                return false;
            }

            return DB::table($parameters[0])
                ->where($parameters[1] ?? $attribute, $decoded)
                ->exists();
        }, __('validation.exists'));
    }
}
