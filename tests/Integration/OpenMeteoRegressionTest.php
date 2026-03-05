<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Integration;

use Orionphp\OpenMeteo\Factory\ForecastFactory;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use Orionphp\OpenMeteo\Enum\Locale;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use PHPUnit\Framework\TestCase;

final class OpenMeteoRegressionTest extends TestCase
{
    public function testRealApiResponseStillParses(): void
    {
        $json = file_get_contents(
            __DIR__ . '/../Fixtures/openmeteo-response.json'
        );

        $data = json_decode($json, true);

        $request = new ForecastRequest(
            latitude: 50,
            longitude: 8,
            models: [WeatherModel::GFS],
            locale: Locale::EN,
            timezone: null
        );

        $forecast = ForecastFactory::fromApiResponse($data, $request);

        $this->assertTrue($forecast->hasCurrent());
        $this->assertTrue($forecast->hasHourly());
        $this->assertTrue($forecast->hasDaily());
    }
}