<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Enum;

use BackedEnum;

/**
 * This trait includes methods to retrieve the list of values and names for all cases in the enum.
 */
trait BackedEnumValuesTrait
{
    /**
     * @return list<string|int>
     */
    public static function values(): array
    {
        return array_map(
            static fn (BackedEnum $case) => $case->value,
            self::cases()
        );
    }

    /**
     * @return list<string>
     */
    public static function names(): array
    {
        return array_map(
            static fn (BackedEnum $case) => $case->name,
            self::cases()
        );
    }

    /**
     * @return array<string, string|int>
     */
    public static function map(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }
}
