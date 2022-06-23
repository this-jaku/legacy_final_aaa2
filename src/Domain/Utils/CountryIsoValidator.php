<?php

namespace App\Domain\Utils;

class CountryIsoValidator
{
    public const ALLOWED_ISO = ['US', 'FR', 'DE'];

    public static function validate(string $code): bool
    {
        return in_array($code, self::ALLOWED_ISO);
    }
}
