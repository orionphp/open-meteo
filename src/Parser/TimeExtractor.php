<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Parser;

use function is_array;
use function is_string;

final class TimeExtractor
{
    /**
     * @param array<string, mixed> $section
     * @return array{0: list<string>, 1: array<string, mixed>}
     */
    public static function extractList(array $section): array
    {
        if (!isset($section['time']) || !is_array($section['time'])) {
            return [[], $section];
        }

        ApiStructureValidator::validateTimeSeries($section);

        /** @var list<string> $time */
        $time = $section['time'];

        unset($section['time']);

        return [$time, $section];
    }

    /**
     * @param array<string, mixed> $section
     * @return array{0: string|null, 1: array<string, mixed>}
     */
    public static function extractSingle(array $section): array
    {
        if (!isset($section['time']) || !is_string($section['time'])) {
            return [null, $section];
        }

        ApiStructureValidator::validateSingleTime($section);

        $time = $section['time'];

        unset($section['time']);

        return [$time, $section];
    }
}
