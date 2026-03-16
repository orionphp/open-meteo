<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Factory;

use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Factory\ForecastFactory;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use Orionphp\OpenMeteo\Response\Forecast;
use PHPUnit\Framework\TestCase;

final class ForecastFactoryTest extends TestCase
{
    public function testFactoryBuildsForecastFromCompleteResponse(): void
    {
        $request = $this->createRequest();

        $data = [
            'current_units' => [
                'temperature_2m' => '°C',
            ],
            'current' => [
                'time' => '2025-01-01T00:00',
                'temperature_2m' => 10,
            ],

            'minutely_15_units' => [
                'temperature_2m' => '°C',
            ],
            'minutely_15' => [
                'time' => ['2025-01-01T00:00'],
                'temperature_2m_gfs' => [10],
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

        $forecast = ForecastFactory::fromApiResponse($data, $request);

        $this->assertInstanceOf(Forecast::class, $forecast);

        $this->assertTrue($forecast->hasCurrent());
        $this->assertTrue($forecast->hasMinutely15());
        $this->assertTrue($forecast->hasHourly());
        $this->assertTrue($forecast->hasDaily());

        $this->assertNotNull($forecast->current);
        $this->assertNotNull($forecast->minutely15);
        $this->assertNotNull($forecast->hourly);
        $this->assertNotNull($forecast->daily);
    }

    public function testMissingSectionsReturnNull(): void
    {
        $request = $this->createRequest();

        $forecast = ForecastFactory::fromApiResponse([], $request);

        $this->assertFalse($forecast->hasCurrent());
        $this->assertFalse($forecast->hasMinutely15());
        $this->assertFalse($forecast->hasHourly());
        $this->assertFalse($forecast->hasDaily());

        $this->assertNull($forecast->current);
        $this->assertNull($forecast->minutely15);
        $this->assertNull($forecast->hourly);
        $this->assertNull($forecast->daily);
    }

    public function testNonArraySectionsAreIgnored(): void
    {
        $request = $this->createRequest();

        $data = [
            'current' => 'invalid',
            'minutely_15' => 'invalid',
            'hourly' => 'invalid',
            'daily' => 'invalid',
        ];

        $forecast = ForecastFactory::fromApiResponse($data, $request);

        $this->assertFalse($forecast->hasCurrent());
        $this->assertFalse($forecast->hasMinutely15());
        $this->assertFalse($forecast->hasHourly());
        $this->assertFalse($forecast->hasDaily());
    }

    public function testNonArrayUnitsAreIgnored(): void
    {
        $request = $this->createRequest();

        $data = [
            'current_units' => 'invalid',
            'current' => [
                'time' => '2025-01-01T00:00',
                'temperature_2m' => 10,
            ],
        ];

        $forecast = ForecastFactory::fromApiResponse($data, $request);

        $this->assertTrue($forecast->hasCurrent());
        $this->assertNotNull($forecast->current);
    }

    private function createRequest(): ForecastRequest
    {
        return new ForecastRequest(
            latitude: 50.0,
            longitude: 8.0,
            models: [WeatherModel::GFS],
            timezone: null
        );
    }
}
