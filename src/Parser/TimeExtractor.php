<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Parser;

use function is_array;
use function is_string;

final class TimeExtractor
{
    /**
     * Extracts a time list (hourly/daily).
     *
     * @param array<string, mixed> $section
     * @return array{0: list<string>, 1: array<string, mixed>}
     */
    public static function extractList(array $section): array
    {
        $time = [];

        if (isset($section['time']) && is_array($section['time'])) {
            $time = $section['time'];
            /** @var list<string> $time */
            unset($section['time']);
        }

        return [$time, $section];
    }

    /**
     * Extracts a single timestamp (current).
     *
     * @param array<string, mixed> $section
     * @return array{0: string|null, 1: array<string, mixed>}
     */
    public static function extractSingle(array $section): array
    {
        $time = null;

        if (isset($section['time']) && is_string($section['time'])) {
            $time = $section['time'];
            unset($section['time']);
        }

        return [$time, $section];
    }
}
