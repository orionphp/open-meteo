<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Parser;

use function count;
use function is_array;
use function is_string;

use Orionphp\OpenMeteo\Exception\OpenMeteoException;

final class ApiStructureValidator
{
    /**
     * Validates a time-series section.
     *
     * @param array<string,mixed> $section
     */
    public static function validateTimeSeries(array $section): void
    {
        if (!isset($section['time']) || !is_array($section['time'])) {
            throw new OpenMeteoException('API response missing "time" array.');
        }

        foreach ($section['time'] as $value) {
            if (!is_string($value)) {
                throw new OpenMeteoException('Invalid time value in API response.');
            }
        }

        $timeCount = count($section['time']);

        foreach ($section as $field => $values) {

            if ($field === 'time') {
                continue;
            }

            if (!is_array($values)) {
                throw new OpenMeteoException(
                    sprintf('Field "%s" must be array.', $field)
                );
            }

            if (count($values) !== $timeCount) {
                throw new OpenMeteoException(
                    sprintf(
                        'Length mismatch: "%s" (%d) vs time (%d).',
                        $field,
                        count($values),
                        $timeCount
                    )
                );
            }
        }
    }

    /**
     * Validates a single timestamp section (current).
     *
     * @param array<string,mixed> $section
     */
    public static function validateSingleTime(array $section): void
    {
        if (isset($section['time']) && !is_string($section['time'])) {
            throw new OpenMeteoException('API response "time" must be string.');
        }
    }
}
