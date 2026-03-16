<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Unit\Enum;

use Orionphp\OpenMeteo\Enum\Minutely15Field;
use PHPUnit\Framework\TestCase;
use ValueError;

final class Minutely15FieldTest extends TestCase
{
    public function testItHasExpectedNumberOfCases(): void
    {
        $this->assertCount(18, Minutely15Field::cases());
    }

    public function testValuesReturnsAllBackedValuesInOrder(): void
    {
        $expected = [
            'temperature_2m',
            'relative_humidity_2m',
            'dew_point_2m',
            'apparent_temperature',

            'precipitation',
            'rain',
            'snowfall',
            'showers',

            'weathercode',

            'cloud_cover',

            'wind_speed_10m',
            'wind_direction_10m',
            'wind_gusts_10m',

            'visibility',

            'shortwave_radiation',
            'direct_radiation',
            'diffuse_radiation',
            'direct_normal_irradiance',
        ];

        $this->assertSame($expected, Minutely15Field::values());
    }

    public function testNamesReturnsAllCaseNamesInOrder(): void
    {
        $expected = [
            'TEMPERATURE_2M',
            'RELATIVE_HUMIDITY_2M',
            'DEW_POINT_2M',
            'APPARENT_TEMPERATURE',

            'PRECIPITATION',
            'RAIN',
            'SNOWFALL',
            'SHOWERS',

            'WEATHER_CODE',

            'CLOUD_COVER',

            'WIND_SPEED_10M',
            'WIND_DIRECTION_10M',
            'WIND_GUSTS_10M',

            'VISIBILITY',

            'SHORTWAVE_RADIATION',
            'DIRECT_RADIATION',
            'DIFFUSE_RADIATION',
            'DIRECT_NORMAL_IRRADIANCE',
        ];

        $this->assertSame($expected, Minutely15Field::names());
    }

    public function testEachValueMapsCorrectlyUsingFrom(): void
    {
        foreach (Minutely15Field::values() as $value) {
            $enum = Minutely15Field::from($value);

            $this->assertInstanceOf(Minutely15Field::class, $enum);
            $this->assertSame($value, $enum->value);
        }
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        $this->assertSame(null, Minutely15Field::tryFrom('invalid_field'));
    }

    public function testFromThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(ValueError::class);

        Minutely15Field::from('invalid_field');
    }

    public function testValuesContainNoDuplicates(): void
    {
        $values = Minutely15Field::values();

        $this->assertSame($values, array_values(array_unique($values)));
    }

    public function testNamesContainNoDuplicates(): void
    {
        $names = Minutely15Field::names();

        $this->assertSame($names, array_values(array_unique($names)));
    }

    public function testValuesMatchCasesImplementation(): void
    {
        $cases = Minutely15Field::cases();

        $valuesFromCases = array_map(
            static fn (Minutely15Field $case) => $case->value,
            $cases
        );

        $namesFromCases = array_map(
            static fn (Minutely15Field $case) => $case->name,
            $cases
        );

        $this->assertSame($valuesFromCases, Minutely15Field::values());
        $this->assertSame($namesFromCases, Minutely15Field::names());
    }
}
