<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Enum;

use Orionphp\OpenMeteo\Enum\DailyField;
use PHPUnit\Framework\TestCase;

final class DailyFieldTest extends TestCase
{
    public function testItHasExpectedNumberOfCases(): void
    {
        $this->assertCount(20, DailyField::cases());
    }

    public function testValuesReturnsAllBackedValuesInOrder(): void
    {
        $expected = [
            'weathercode',
            'temperature_2m_max',
            'temperature_2m_min',
            'apparent_temperature_max',
            'apparent_temperature_min',
            'precipitation_sum',
            'precipitation_probability_max',
            'rain_sum',
            'showers_sum',
            'snowfall_sum',
            'snow_depth_max',
            'wind_speed_10m_max',
            'wind_gusts_10m_max',
            'wind_direction_10m_dominant',
            'sunrise',
            'sunset',
            'sunshine_duration',
            'uv_index_max',
            'shortwave_radiation_sum',
            'et0_fao_evapotranspiration',
        ];

        $this->assertSame($expected, DailyField::values());
    }

    public function testNamesReturnsAllCaseNamesInOrder(): void
    {
        $expected = [
            'WEATHER_CODE',
            'TEMPERATURE_2M_MAX',
            'TEMPERATURE_2M_MIN',
            'APPARENT_TEMPERATURE_MAX',
            'APPARENT_TEMPERATURE_MIN',
            'PRECIPITATION_SUM',
            'PRECIPITATION_PROBABILITY_MAX',
            'RAIN_SUM',
            'SHOWERS_SUM',
            'SNOWFALL_SUM',
            'SNOW_DEPTH_MAX',
            'WIND_SPEED_10M_MAX',
            'WIND_GUSTS_10M_MAX',
            'WIND_DIRECTION_10M_DOMINANT',
            'SUNRISE',
            'SUNSET',
            'SUNSHINE_DURATION',
            'UV_INDEX_MAX',
            'SHORTWAVE_RADIATION_SUM',
            'ET0_FAO_EVAPOTRANSPIRATION',
        ];

        $this->assertSame($expected, DailyField::names());
    }

    public function testEachValueMapsCorrectlyUsingFrom(): void
    {
        foreach (DailyField::values() as $value) {
            $enum = DailyField::from($value);

            $this->assertInstanceOf(DailyField::class, $enum);
            $this->assertSame($value, $enum->value);
        }
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        $this->assertNull(DailyField::tryFrom('invalid_field'));
    }

    public function testFromThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(\ValueError::class);

        DailyField::from('invalid_field');
    }

    public function testValuesContainNoDuplicates(): void
    {
        $values = DailyField::values();

        $this->assertSame($values, array_values(array_unique($values)));
    }

    public function testNamesContainNoDuplicates(): void
    {
        $names = DailyField::names();

        $this->assertSame($names, array_values(array_unique($names)));
    }

    public function testValuesMatchCasesImplementation(): void
    {
        $cases = DailyField::cases();

        $valuesFromCases = array_map(
            static fn(DailyField $case) => $case->value,
            $cases
        );

        $namesFromCases = array_map(
            static fn(DailyField $case) => $case->name,
            $cases
        );

        $this->assertSame($valuesFromCases, DailyField::values());
        $this->assertSame($namesFromCases, DailyField::names());
    }
}