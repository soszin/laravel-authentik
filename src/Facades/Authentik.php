<?php

namespace Soszin\LaravelAuthentik\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array introspectToken(string $token)
 * @method static null|array refreshToken(string $refreshToken)
 */
class Authentik extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'authentik';
    }
}
