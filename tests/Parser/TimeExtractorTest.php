<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Parser;

use Orionphp\OpenMeteo\Parser\TimeExtractor;
use PHPUnit\Framework\TestCase;

final class TimeExtractorTest extends TestCase
{
    public function testExtractListReturnsEmptyTimeIfNotPresent(): void
    {
        $section = [
            'temperature' => [1, 2, 3],
        ];

        [$time, $remaining] = TimeExtractor::extractList($section);

        $this->assertSame([], $time);
        $this->assertSame($section, $remaining);
    }

    public function testExtractListExtractsTimeArray(): void
    {
        $section = [
            'time' => ['2025-01-01', '2025-01-02'],
            'temperature' => [10, 11],
        ];

        [$time, $remaining] = TimeExtractor::extractList($section);

        $this->assertSame(['2025-01-01', '2025-01-02'], $time);
        $this->assertArrayNotHasKey('time', $remaining);
        $this->assertArrayHasKey('temperature', $remaining);
    }

    public function testExtractListIgnoresNonArrayTime(): void
    {
        $section = [
            'time' => 'invalid',
            'temperature' => [10, 11],
        ];

        [$time, $remaining] = TimeExtractor::extractList($section);

        $this->assertSame([], $time);
        $this->assertSame($section, $remaining);
    }

    public function testExtractSingleReturnsNullIfNotPresent(): void
    {
        $section = [
            'temperature' => 10,
        ];

        [$time, $remaining] = TimeExtractor::extractSingle($section);

        $this->assertNull($time);
        $this->assertSame($section, $remaining);
    }

    public function testExtractSingleExtractsStringTime(): void
    {
        $section = [
            'time' => '2025-01-01T00:00',
            'temperature' => 10,
        ];

        [$time, $remaining] = TimeExtractor::extractSingle($section);

        $this->assertSame('2025-01-01T00:00', $time);
        $this->assertArrayNotHasKey('time', $remaining);
        $this->assertArrayHasKey('temperature', $remaining);
    }

    public function testExtractSingleIgnoresNonStringTime(): void
    {
        $section = [
            'time' => ['invalid'],
            'temperature' => 10,
        ];

        [$time, $remaining] = TimeExtractor::extractSingle($section);

        $this->assertNull($time);
        $this->assertSame($section, $remaining);
    }
}