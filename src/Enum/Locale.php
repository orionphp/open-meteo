<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Enum;

/**
 * Current "locales" used for the weathercode translations
 */
enum Locale: string
{
    use BackedEnumValuesTrait;

    case DE = 'de';
    case EN = 'en';
    case ES = 'es';
    case FR = 'fr';

    public static function fromString(string $locale): self
    {
        $normalized = strtolower(substr($locale, 0, 2));

        return self::tryFrom($normalized) ?? self::EN;
    }
}
