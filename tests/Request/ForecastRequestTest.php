<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Request;

use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use PHPUnit\Framework\TestCase;

final class ForecastRequestTest extends TestCase
{
    public function testConstructorSetsAllProperties(): void
    {
        $request = new ForecastRequest(
            latitude: 50.0,
            longitude: 8.0,
            models: [WeatherModel::GFS],
            timezone: 'Europe/Berlin',
            current: [CurrentField::TEMPERATURE_2M],
            hourly: [HourlyField::TEMPERATURE_2M],
            daily: [DailyField::TEMPERATURE_2M_MAX]
        );

        $this->assertSame(50.0, $request->latitude);
        $this->assertSame(8.0, $request->longitude);
        $this->assertSame([WeatherModel::GFS], $request->models);
        $this->assertSame('Europe/Berlin', $request->timezone);
        $this->assertSame([CurrentField::TEMPERATURE_2M], $request->current);
        $this->assertSame([HourlyField::TEMPERATURE_2M], $request->hourly);
        $this->assertSame([DailyField::TEMPERATURE_2M_MAX], $request->daily);
    }

    public function testOptionalFieldsCanBeNull(): void
    {
        $request = new ForecastRequest(
            latitude: 50.0,
            longitude: 8.0,
            models: [WeatherModel::GFS],
            timezone: null
        );

        $this->assertNull($request->timezone);
        $this->assertNull($request->current);
        $this->assertNull($request->hourly);
        $this->assertNull($request->daily);
    }
}
