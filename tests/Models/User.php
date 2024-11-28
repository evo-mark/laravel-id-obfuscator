<?php

namespace EvoMark\LaravelIdObfuscator\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use EvoMark\LaravelIdObfuscator\Traits\Obfuscatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Obfuscatable;

    protected static function newFactory()
    {
        return \EvoMark\LaravelIdObfuscator\Tests\Database\Factories\UserFactory::new();
    }
}
