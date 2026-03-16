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
        $this->assertCount(31, HourlyField::cases());
    }

    public function testValuesReturnsAllBackedValuesInOrder(): void
    {
        $expected = [
            'weathercode',

            'temperature_2m',
            'apparent_temperature',

            'soil_temperature_0cm',
            'soil_temperature_6cm',
            'soil_temperature_18cm',
            'soil_temperature_54cm',

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

            'dew_point_2m',

            'shortwave_radiation',
            'direct_radiation',
            'diffuse_radiation',
            'direct_normal_irradiance',

            'evapotranspiration',
            'et0_fao_evapotranspiration',
            'vapour_pressure_deficit',

            'cape',
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
            'SOIL_TEMPERATURE_6CM',
            'SOIL_TEMPERATURE_18CM',
            'SOIL_TEMPERATURE_54CM',

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

            'DEW_POINT_2M',

            'SHORTWAVE_RADIATION',
            'DIRECT_RADIATION',
            'DIFFUSE_RADIATION',
            'DIRECT_NORMAL_IRRADIANCE',

            'EVAPOTRANSPIRATION',
            'ET0_FAO_EVAPOTRANSPIRATION',
            'VAPOUR_PRESSURE_DEFICIT',

            'CAPE',
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
        $this->assertSame(null, HourlyField::tryFrom('invalid_field'));
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
            static fn (HourlyField $case) => $case->value,
            $cases
        );

        $namesFromCases = array_map(
            static fn (HourlyField $case) => $case->name,
            $cases
        );

        $this->assertSame($valuesFromCases, HourlyField::values());
        $this->assertSame($namesFromCases, HourlyField::names());
    }
}
