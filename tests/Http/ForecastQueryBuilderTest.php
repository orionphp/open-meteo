<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Http;

use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\Locale;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Http\ForecastQueryBuilder;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use PHPUnit\Framework\TestCase;

final class ForecastQueryBuilderTest extends TestCase
{
    public function testBuildWithMinimumRequiredFields(): void
    {
        $request = new ForecastRequest(
            latitude: 50.0,
            longitude: 8.0,
            models: [WeatherModel::GFS],
            locale: Locale::EN,
            timezone: null
        );

        $query = ForecastQueryBuilder::build($request);

        $this->assertSame([
            'latitude' => 50.0,
            'longitude' => 8.0,
            'models' => 'gfs',
        ], $query);
    }

    public function testTimezoneIsIncludedIfValid(): void
    {
        $request = new ForecastRequest(
            latitude: 50.0,
            longitude: 8.0,
            models: [WeatherModel::GFS],
            locale: Locale::EN,
            timezone: 'Europe/Berlin'
        );

        $query = ForecastQueryBuilder::build($request);

        $this->assertSame('Europe/Berlin', $query['timezone']);
    }

    public function testEmptyTimezoneIsIgnored(): void
    {
        $request = new ForecastRequest(
            latitude: 50.0,
            longitude: 8.0,
            models: [WeatherModel::ICON_D2],
            locale: Locale::EN,
            timezone: ''
        );

        $query = ForecastQueryBuilder::build($request);

        $this->assertArrayNotHasKey('timezone', $query);
    }

    public function testCurrentHourlyDailyAreImploded(): void
    {
        $request = new ForecastRequest(
            latitude: 50.0,
            longitude: 8.0,
            models: [WeatherModel::ECMWF_IFS],
            locale: Locale::EN,
            timezone: null,
            current: [CurrentField::TEMPERATURE_2M, CurrentField::WEATHER_CODE],
            hourly: [HourlyField::TEMPERATURE_2M],
            daily: [DailyField::TEMPERATURE_2M_MAX]
        );

        $query = ForecastQueryBuilder::build($request);

        $this->assertSame(
            'temperature_2m,weathercode',
            $query['current']
        );

        $this->assertSame(
            'temperature_2m',
            $query['hourly']
        );

        $this->assertSame(
            'temperature_2m_max',
            $query['daily']
        );
    }

    public function testEmptyFieldArraysAreIgnored(): void
    {
        $request = new ForecastRequest(
            latitude: 50.0,
            longitude: 8.0,
            models: [WeatherModel::ECMWF_IFS],
            locale: Locale::EN,
            timezone: null,
            current: [],
            hourly: [],
            daily: []
        );

        $query = ForecastQueryBuilder::build($request);

        $this->assertArrayNotHasKey('current', $query);
        $this->assertArrayNotHasKey('hourly', $query);
        $this->assertArrayNotHasKey('daily', $query);
    }

    public function testMultipleModelsAreImplodedInOrder(): void
    {
        $request = new ForecastRequest(
            latitude: 50.0,
            longitude: 8.0,
            models: [
                WeatherModel::GFS,
                WeatherModel::ICON_GLOBAL,
            ],
            locale: Locale::EN,
            timezone: null
        );

        $query = ForecastQueryBuilder::build($request);

        $this->assertSame(
            'gfs,icon_global',
            $query['models']
        );
    }
}