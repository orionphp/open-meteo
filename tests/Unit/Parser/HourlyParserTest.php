<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Unit\Parser;

use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Parser\HourlyParser;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use Orionphp\OpenMeteo\Response\Hourly\HourlyData;
use PHPUnit\Framework\TestCase;

final class HourlyParserTest extends TestCase
{
    public function testParseReturnsNullIfSectionIsNull(): void
    {
        $request = $this->createRequest([]);

        $this->assertNull(
            HourlyParser::parse(null, null, $request)
        );
    }

    public function testParseBuildsHourlyDataCorrectly(): void
    {
        $request = $this->createRequest([
            WeatherModel::GFS,
            WeatherModel::ICON_GLOBAL,
        ]);

        $section = [
            'time' => ['2025-01-01T00:00', '2025-01-01T01:00'],
            'temperature_2m_gfs' => [10, 11],
            'temperature_2m_icon_global' => [12, 13],
        ];

        $units = [
            'temperature_2m' => '°C',
        ];

        $result = HourlyParser::parse($section, $units, $request);

        $this->assertInstanceOf(HourlyData::class, $result);

        $this->assertSame(
            ['2025-01-01T00:00', '2025-01-01T01:00'],
            $result->time()
        );

        $field = $result->field(HourlyField::TEMPERATURE_2M);

        $this->assertNotNull($field);
        $this->assertSame('°C', $field->unit());

        $this->assertSame(
            [10, 11],
            $field->values(WeatherModel::GFS)
        );

        $this->assertSame(
            [12, 13],
            $field->values(WeatherModel::ICON_GLOBAL)
        );

        $this->assertSame(
            [
                WeatherModel::GFS,
                WeatherModel::ICON_GLOBAL
            ],
            $field->models()
        );
    }

    public function testUnknownFieldsAreIgnored(): void
    {
        $request = $this->createRequest([
            WeatherModel::GFS,
        ]);

        $section = [
            'time' => ['2025-01-01T00:00'],
            'unknown_field_gfs' => [1],
        ];

        $result = HourlyParser::parse($section, null, $request);

        $this->assertInstanceOf(HourlyData::class, $result);
        $this->assertSame([], $result->availableFields());
    }

    /**
     * @param list<WeatherModel> $models
     */
    private function createRequest(array $models): ForecastRequest
    {
        return new ForecastRequest(
            latitude: 50.0,
            longitude: 8.0,
            models: $models,
            timezone: null,
            current: null,
            hourly: null,
            daily: null
        );
    }
}
