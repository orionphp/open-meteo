<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\OpenMeteoClient;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use PHPUnit\Framework\TestCase;

final class FullStackLiveTest extends TestCase
{
    public function testFullForecastStack(): void
    {
        if (getenv('OPEN_METEO_INTEGRATION_TESTS') !== '1') {
            $this->markTestSkipped('Integration tests disabled.');
        }

        $client = new OpenMeteoClient(
            new Client(),
            new HttpFactory()
        );

        $request = new ForecastRequest(
            latitude: 52.52,
            longitude: 13.41,
            models: [
                WeatherModel::ECMWF_IFS
            ],
            timezone: 'Europe/Berlin',
            current: [
                CurrentField::TEMPERATURE_2M,
                CurrentField::WEATHER_CODE,
            ],
            hourly: [
                HourlyField::TEMPERATURE_2M,
                HourlyField::PRECIPITATION,
            ],
            daily: [
                DailyField::TEMPERATURE_2M_MAX,
                DailyField::TEMPERATURE_2M_MIN,
            ]
        );

        $forecast = $client->forecast($request);

        $hourly = $forecast->hourly;
        $this->assertNotNull($hourly);

        $hourlyField = $hourly->field(HourlyField::TEMPERATURE_2M);
        $this->assertNotNull($hourlyField);

        $models = $hourlyField->models();
        $this->assertNotEmpty($models);

        $daily = $forecast->daily;
        $this->assertNotNull($daily);

        $dailyField = $daily->field(DailyField::TEMPERATURE_2M_MAX);
        $this->assertNotNull($dailyField);
    }
}
