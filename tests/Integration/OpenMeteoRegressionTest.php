<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Integration;

use JsonException;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Factory\ForecastFactory;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use PHPUnit\Framework\TestCase;

final class OpenMeteoRegressionTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testRealApiResponseStillParses(): void
    {
        $json = file_get_contents(
            __DIR__ . '/../Fixtures/openmeteo-response.json'
        );

        self::assertIsString($json);

        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        /** @var array<string, mixed> $data */

        $request = new ForecastRequest(
            latitude: 50,
            longitude: 8,
            models: [WeatherModel::GFS],
            timezone: null
        );

        $forecast = ForecastFactory::fromApiResponse($data, $request);

        $this->assertTrue($forecast->hasCurrent());
        $this->assertTrue($forecast->hasHourly());
        $this->assertTrue($forecast->hasDaily());
    }
}
