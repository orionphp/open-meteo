<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\OpenMeteoClient;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use PHPUnit\Framework\TestCase;

final class ForecastSmokeTest extends TestCase
{
    public function testForecastRequestWorks(): void
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
            models: [WeatherModel::ECMWF_IFS],
            timezone: 'Europe/Berlin',
            hourly: [HourlyField::TEMPERATURE_2M]
        );

        $forecast = $client->forecast($request);

        $hourly = $forecast->hourly;
        $this->assertNotNull($hourly);

        $field = $hourly->field(HourlyField::TEMPERATURE_2M);
        $this->assertNotNull($field);

        $this->assertNotEmpty($field->models());
    }
}
