<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Example;

use Orionphp\OpenMeteo\Factory\ForecastFactory;
use Orionphp\OpenMeteo\Request\ForecastRequestBuilder;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Response\Forecast;
use PHPUnit\Framework\TestCase;

final class QuickstartExampleTest extends TestCase
{
    public function testQuickstartExample(): void
    {
        // build request
        $request = ForecastRequestBuilder::create(50.0, 8.0)
            ->models(WeatherModel::GFS)
            ->current(CurrentField::TEMPERATURE_2M)
            ->hourly(HourlyField::TEMPERATURE_2M)
            ->daily(DailyField::TEMPERATURE_2M_MAX)
            ->build();

        // example API response (simplified)
        $data = [
            'current_units' => [
                'temperature_2m' => '°C',
            ],
            'current' => [
                'time' => '2025-01-01T00:00',
                'temperature_2m' => 10,
            ],
            'hourly_units' => [
                'temperature_2m' => '°C',
            ],
            'hourly' => [
                'time' => ['2025-01-01T00:00'],
                'temperature_2m_gfs' => [10],
            ],
            'daily_units' => [
                'temperature_2m_max' => '°C',
            ],
            'daily' => [
                'time' => ['2025-01-01'],
                'temperature_2m_max_gfs' => [12],
            ],
        ];

        // parse response
        $forecast = ForecastFactory::fromApiResponse($data, $request);

        $this->assertInstanceOf(Forecast::class, $forecast);

        // read results
        $this->assertTrue($forecast->hasCurrent());
        $this->assertTrue($forecast->hasHourly());
        $this->assertTrue($forecast->hasDaily());
    }
}