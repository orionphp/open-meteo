<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Unit\Parser;

use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Parser\MultiModelParser;
use PHPUnit\Framework\TestCase;

final class MultiModelParserTest extends TestCase
{
    public function testSingleModelWithoutSuffix(): void
    {
        $section = [
            'temperature_2m_max' => [10, 11],
        ];

        $result = MultiModelParser::parse(
            $section,
            null,
            ['gfs'],
            DailyField::class,
            static fn ($enum, $unit, $values) => [$enum, $unit, $values]
        );

        $this->assertArrayHasKey('temperature_2m_max', $result);
        $this->assertSame(
            ['gfs' => [10, 11]],
            $result['temperature_2m_max'][2]
        );
    }

    public function testMultipleModelsWithoutSuffix(): void
    {
        $section = [
            'temperature_2m_max' => [10, 11],
        ];

        $result = MultiModelParser::parse(
            $section,
            null,
            ['gfs', 'icon_global'],
            DailyField::class,
            static fn ($enum, $unit, $values) => [$enum, $unit, $values]
        );

        $this->assertSame(
            [
                'gfs' => [10, 11],
                'icon_global' => [10, 11],
            ],
            $result['temperature_2m_max'][2]
        );
    }

    public function testSuffixIsRespected(): void
    {
        $section = [
            'temperature_2m_max_gfs' => [1, 2],
            'temperature_2m_max_icon_global' => [3, 4],
        ];

        $result = MultiModelParser::parse(
            $section,
            null,
            ['gfs', 'icon_global'],
            DailyField::class,
            static fn ($enum, $unit, $values) => [$enum, $unit, $values]
        );

        $this->assertSame(
            [
                'gfs' => [1, 2],
                'icon_global' => [3, 4],
            ],
            $result['temperature_2m_max'][2]
        );
    }

    public function testNonArrayValuesAreIgnored(): void
    {
        $section = [
            'temperature_2m_max' => 123,
        ];

        $result = MultiModelParser::parse(
            $section,
            null,
            ['gfs'],
            DailyField::class,
            static fn ($enum, $unit, $values) => [$enum, $unit, $values]
        );

        $this->assertSame([], $result);
    }

    public function testUnknownEnumFieldIsIgnored(): void
    {
        $section = [
            'unknown_field' => [1, 2],
        ];

        $result = MultiModelParser::parse(
            $section,
            null,
            ['gfs'],
            DailyField::class,
            static fn ($enum, $unit, $values) => [$enum, $unit, $values]
        );

        $this->assertSame([], $result);
    }

    public function testUnitResolutionBaseKey(): void
    {
        $section = [
            'temperature_2m_max' => [10],
        ];

        $units = [
            'temperature_2m_max' => '°C',
        ];

        $result = MultiModelParser::parse(
            $section,
            $units,
            ['gfs'],
            DailyField::class,
            static fn ($enum, $unit, $values) => [$enum, $unit, $values]
        );

        $this->assertSame('°C', $result['temperature_2m_max'][1]);
    }

    public function testUnitResolutionWithSuffix(): void
    {
        $section = [
            'temperature_2m_max_gfs' => [10],
        ];

        $units = [
            'temperature_2m_max_gfs' => '°C',
        ];

        $result = MultiModelParser::parse(
            $section,
            $units,
            ['gfs'],
            DailyField::class,
            static fn ($enum, $unit, $values) => [$enum, $unit, $values]
        );

        $this->assertSame('°C', $result['temperature_2m_max'][1]);
    }

    public function testNonStringUnitIsIgnored(): void
    {
        $section = [
            'temperature_2m_max' => [10],
        ];

        $units = [
            'temperature_2m_max' => 123,
        ];

        $result = MultiModelParser::parse(
            $section,
            $units,
            ['gfs'],
            DailyField::class,
            static fn ($enum, $unit, $values) => [$enum, $unit, $values]
        );

        $this->assertNull($result['temperature_2m_max'][1]);
    }
}
