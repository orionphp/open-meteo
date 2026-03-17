<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Parser;

use function is_scalar;
use function is_string;

use Orionphp\OpenMeteo\Enum\CurrentField;

use Orionphp\OpenMeteo\Response\Current\CurrentData;
use Orionphp\OpenMeteo\Response\Current\CurrentFieldData;

final class CurrentParser
{
    /**
     * @param array<string, mixed>|null $section
     * @param array<string, mixed>|null $units
     */
    public static function parse(
        ?array $section,
        ?array $units
    ): ?CurrentData {

        if ($section === null) {
            return null;
        }

        $time = null;

        [$time, $section] = TimeExtractor::extractSingle($section);

        /** @var array<string, CurrentFieldData> $fields */
        $fields = [];

        foreach ($section as $key => $value) {

            $fieldEnum = CurrentField::tryFrom($key);
            if ($fieldEnum === null) {
                continue;
            }

            if (!is_scalar($value) && $value !== null) {
                continue;
            }

            /** @var float|int|string|null $value */

            $unit = null;
            if ($units !== null && isset($units[$key]) && is_string($units[$key])) {
                $unit = $units[$key];
            }

            $fields[$key] = new CurrentFieldData(
                field: $fieldEnum,
                unit: $unit,
                value: $value
            );
        }

        return new CurrentData(
            time: $time,
            fields: $fields
        );
    }
}
