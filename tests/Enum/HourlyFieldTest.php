<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Enum;

use Orionphp\OpenMeteo\Enum\HourlyField;
use PHPUnit\Framework\TestCase;
use ValueError;

final class HourlyFieldTest extends TestCase
{
    public function testItHasExpectedNumberOfCases(): void
    {
        $this->assertCount(18, HourlyField::cases());
    }

    public function testValuesReturnsAllBackedValuesInOrder(): void
    {
        $expected = [
            'weathercode',
            'temperature_2m',
            'apparent_temperature',
            'soil_temperature_0cm',
            'cloud_cover',
            'relative_humidity_2m',
            'visibility',
            'wind_speed_10m',
            'wind_gusts_10m',
            'wind_direction_10m',
            'precipitation',
            'precipitation_probability',
            'rain',
            'snowfall',
            'snow_depth',
            'uv_index',
            'dew_point_2m',
            'pressure_msl',
        ];

        $this->assertSame($expected, HourlyField::values());
    }

    public function testNamesReturnsAllCaseNamesInOrder(): void
    {
        $expected = [
            'WEATHER_CODE',
            'TEMPERATURE_2M',
            'APPARENT_TEMPERATURE',
            'SOIL_TEMPERATURE_0CM',
            'CLOUD_COVER',
            'RELATIVE_HUMIDITY_2M',
            'VISIBILITY',
            'WIND_SPEED_10M',
            'WIND_GUSTS_10M',
            'WIND_DIRECTION_10M',
            'PRECIPITATION',
            'PRECIPITATION_PROBABILITY',
            'RAIN',
            'SNOWFALL',
            'SNOW_DEPTH',
            'UV_INDEX',
            'DEW_POINT_2M',
            'PRESSURE_MSL',
        ];

        $this->assertSame($expected, HourlyField::names());
    }

    public function testEachValueMapsCorrectlyUsingFrom(): void
    {
        foreach (HourlyField::values() as $value) {
            $enum = HourlyField::from($value);

            $this->assertInstanceOf(HourlyField::class, $enum);
            $this->assertSame($value, $enum->value);
        }
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        $this->assertNull(HourlyField::tryFrom('invalid_field'));
    }

    public function testFromThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(ValueError::class);

        HourlyField::from('invalid_field');
    }

    public function testValuesContainNoDuplicates(): void
    {
        $values = HourlyField::values();

        $this->assertSame($values, array_values(array_unique($values)));
    }

    public function testNamesContainNoDuplicates(): void
    {
        $names = HourlyField::names();

        $this->assertSame($names, array_values(array_unique($names)));
    }

    public function testValuesMatchCasesImplementation(): void
    {
        $cases = HourlyField::cases();

        $valuesFromCases = array_map(
            static fn(HourlyField $case) => $case->value,
            $cases
        );

        $namesFromCases = array_map(
            static fn(HourlyField $case) => $case->name,
            $cases
        );

        $this->assertSame($valuesFromCases, HourlyField::values());
        $this->assertSame($namesFromCases, HourlyField::names());
    }
}