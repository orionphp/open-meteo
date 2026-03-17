<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Unit\Parser;

use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Parser\DailyParser;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use Orionphp\OpenMeteo\Response\Daily\DailyData;
use PHPUnit\Framework\TestCase;

final class DailyParserTest extends TestCase
{
    public function testParseReturnsNullIfSectionIsNull(): void
    {
        $request = $this->createRequest([]);

        $this->assertNull(
            DailyParser::parse(null, null, $request)
        );
    }

    public function testParseBuildsDailyDataCorrectly(): void
    {
        $request = $this->createRequest([
            WeatherModel::GFS,
            WeatherModel::ICON_GLOBAL,
        ]);

        $section = [
            'time' => ['2025-01-01', '2025-01-02'],
            'temperature_2m_max_gfs' => [10, 11],
            'temperature_2m_max_icon_global' => [12, 13],
        ];

        $units = [
            'temperature_2m_max' => '°C',
        ];

        $result = DailyParser::parse($section, $units, $request);

        $this->assertInstanceOf(DailyData::class, $result);

        $this->assertSame(
            ['2025-01-01', '2025-01-02'],
            $result->time()
        );

        $field = $result->field(DailyField::TEMPERATURE_2M_MAX);

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
            'time' => ['2025-01-01'],
            'unknown_field_gfs' => [1],
        ];

        $result = DailyParser::parse($section, null, $request);

        $this->assertInstanceOf(DailyData::class, $result);
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
