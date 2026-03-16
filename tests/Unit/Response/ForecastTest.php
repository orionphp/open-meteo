<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Unit\Response;

use Orionphp\OpenMeteo\Response\Current\CurrentData;
use Orionphp\OpenMeteo\Response\Daily\DailyData;
use Orionphp\OpenMeteo\Response\Forecast;
use Orionphp\OpenMeteo\Response\Hourly\HourlyData;
use Orionphp\OpenMeteo\Response\Minutely15\Minutely15Data;
use PHPUnit\Framework\TestCase;

final class ForecastTest extends TestCase
{
    public function testConstructorAssignsAllSections(): void
    {
        $current = new CurrentData('2025-01-01T00:00', []);
        $minutely = new Minutely15Data([], []);
        $hourly = new HourlyData([], []);
        $daily = new DailyData([], []);

        $forecast = new Forecast(
            current: $current,
            minutely15: $minutely,
            hourly: $hourly,
            daily: $daily
        );

        $this->assertSame($current, $forecast->current);
        $this->assertSame($minutely, $forecast->minutely15);
        $this->assertSame($hourly, $forecast->hourly);
        $this->assertSame($daily, $forecast->daily);
    }

    public function testHasMethodsReturnTrueWhenDataExists(): void
    {
        $forecast = new Forecast(
            current: new CurrentData('2025-01-01T00:00', []),
            minutely15: new Minutely15Data([], []),
            hourly: new HourlyData([], []),
            daily: new DailyData([], [])
        );

        $this->assertTrue($forecast->hasCurrent());
        $this->assertTrue($forecast->hasMinutely15());
        $this->assertTrue($forecast->hasHourly());
        $this->assertTrue($forecast->hasDaily());
    }

    public function testHasMethodsReturnFalseWhenDataIsNull(): void
    {
        $forecast = new Forecast(
            current: null,
            minutely15: null,
            hourly: null,
            daily: null
        );

        $this->assertFalse($forecast->hasCurrent());
        $this->assertFalse($forecast->hasMinutely15());
        $this->assertFalse($forecast->hasHourly());
        $this->assertFalse($forecast->hasDaily());
    }
}
