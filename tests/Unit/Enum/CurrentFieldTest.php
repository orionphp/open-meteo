<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Unit\Enum;

use Orionphp\OpenMeteo\Enum\CurrentField;
use PHPUnit\Framework\TestCase;
use ValueError;

final class CurrentFieldTest extends TestCase
{
    public function testItHasExpectedNumberOfCases(): void
    {
        $this->assertCount(20, CurrentField::cases());
    }

    public function testValuesReturnsAllBackedValuesInOrder(): void
    {
        $expected = [
            'weathercode',
            'temperature_2m',
            'apparent_temperature',
            'cloud_cover',
            'relative_humidity_2m',
            'visibility',
            'pressure_msl',
            'surface_pressure',
            'wind_speed_10m',
            'wind_gusts_10m',
            'wind_direction_10m',
            'precipitation',
            'precipitation_probability',
            'rain',
            'showers',
            'snowfall',
            'snow_depth',
            'uv_index',
            'is_day',
            'dew_point_2m',
        ];

        $this->assertSame($expected, CurrentField::values());
    }

    public function testNamesReturnsAllCaseNamesInOrder(): void
    {
        $expected = [
            'WEATHER_CODE',
            'TEMPERATURE_2M',
            'APPARENT_TEMPERATURE',
            'CLOUD_COVER',
            'RELATIVE_HUMIDITY_2M',
            'VISIBILITY',
            'PRESSURE_MSL',
            'SURFACE_PRESSURE',
            'WIND_SPEED_10M',
            'WIND_GUSTS_10M',
            'WIND_DIRECTION_10M',
            'PRECIPITATION',
            'PRECIPITATION_PROBABILITY',
            'RAIN',
            'SHOWERS',
            'SNOWFALL',
            'SNOW_DEPTH',
            'UV_INDEX',
            'IS_DAY',
            'DEW_POINT_2M',
        ];

        $this->assertSame($expected, CurrentField::names());
    }

    public function testEachValueMapsCorrectlyUsingFrom(): void
    {
        foreach (CurrentField::values() as $value) {
            $enum = CurrentField::from($value);

            $this->assertInstanceOf(CurrentField::class, $enum);
            $this->assertSame($value, $enum->value);
        }
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        $this->assertSame(null, CurrentField::tryFrom('invalid_field'));
    }

    public function testFromThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(ValueError::class);

        CurrentField::from('invalid_field');
    }

    public function testValuesContainNoDuplicates(): void
    {
        $values = CurrentField::values();

        $this->assertSame($values, array_values(array_unique($values)));
    }

    public function testNamesContainNoDuplicates(): void
    {
        $names = CurrentField::names();

        $this->assertSame($names, array_values(array_unique($names)));
    }

    public function testValuesMatchCasesImplementation(): void
    {
        $cases = CurrentField::cases();

        $valuesFromCases = array_map(
            static fn (CurrentField $case) => $case->value,
            $cases
        );

        $namesFromCases = array_map(
            static fn (CurrentField $case) => $case->name,
            $cases
        );

        $this->assertSame($valuesFromCases, CurrentField::values());
        $this->assertSame($namesFromCases, CurrentField::names());
    }
}
