<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Request;

use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Exception\InvalidCoordinatesException;
use Orionphp\OpenMeteo\Exception\InvalidTimezoneException;
use Orionphp\OpenMeteo\Exception\OpenMeteoException;
use Orionphp\OpenMeteo\Request\ForecastRequestBuilder;
use PHPUnit\Framework\TestCase;

final class ForecastRequestBuilderTest extends TestCase
{
    public function testCreateWithValidCoordinates(): void
    {
        $builder = ForecastRequestBuilder::create(50.0, 8.0);

        $request = $builder
            ->models(WeatherModel::GFS)
            ->build();

        $this->assertSame(50.0, $request->latitude);
        $this->assertSame(8.0, $request->longitude);
    }

    public function testInvalidLatitudeThrowsException(): void
    {
        $this->expectException(InvalidCoordinatesException::class);

        ForecastRequestBuilder::create(100.0, 8.0);
    }

    public function testInvalidLongitudeThrowsException(): void
    {
        $this->expectException(InvalidCoordinatesException::class);

        ForecastRequestBuilder::create(50.0, 200.0);
    }

    public function testModelsAreSetAndDuplicatesRemoved(): void
    {
        $request = ForecastRequestBuilder::create(50, 8)
            ->models(
                WeatherModel::GFS,
                WeatherModel::GFS,
                WeatherModel::ICON_GLOBAL
            )
            ->build();

        $this->assertSame(
            [WeatherModel::GFS, WeatherModel::ICON_GLOBAL],
            $request->models
        );
    }

    public function testModelsCannotBeEmpty(): void
    {
        $this->expectException(OpenMeteoException::class);

        ForecastRequestBuilder::create(50, 8)
            ->models();
    }

    public function testValidTimezone(): void
    {
        $request = ForecastRequestBuilder::create(50, 8)
            ->models(WeatherModel::GFS)
            ->timezone('Europe/Berlin')
            ->build();

        $this->assertSame('Europe/Berlin', $request->timezone);
    }

    public function testInvalidTimezoneThrowsException(): void
    {
        $this->expectException(InvalidTimezoneException::class);

        ForecastRequestBuilder::create(50, 8)
            ->models(WeatherModel::GFS)
            ->timezone('Invalid/Timezone');
    }

    public function testCurrentFields(): void
    {
        $request = ForecastRequestBuilder::create(50, 8)
            ->models(WeatherModel::GFS)
            ->current(
                CurrentField::TEMPERATURE_2M,
                CurrentField::TEMPERATURE_2M
            )
            ->build();

        $this->assertSame(
            [CurrentField::TEMPERATURE_2M],
            $request->current
        );
    }

    public function testHourlyFields(): void
    {
        $request = ForecastRequestBuilder::create(50, 8)
            ->models(WeatherModel::GFS)
            ->hourly(HourlyField::TEMPERATURE_2M)
            ->build();

        $this->assertSame(
            [HourlyField::TEMPERATURE_2M],
            $request->hourly
        );
    }

    public function testDailyFields(): void
    {
        $request = ForecastRequestBuilder::create(50, 8)
            ->models(WeatherModel::GFS)
            ->daily(DailyField::TEMPERATURE_2M_MAX)
            ->build();

        $this->assertSame(
            [DailyField::TEMPERATURE_2M_MAX],
            $request->daily
        );
    }

    public function testEmptyCurrentFieldsThrowsException(): void
    {
        $this->expectException(OpenMeteoException::class);

        ForecastRequestBuilder::create(50, 8)
            ->models(WeatherModel::GFS)
            ->current();
    }

    public function testBuildWithoutModelsThrowsException(): void
    {
        $this->expectException(OpenMeteoException::class);

        ForecastRequestBuilder::create(50, 8)->build();
    }
}
