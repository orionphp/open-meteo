<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Enum;

use Orionphp\OpenMeteo\Enum\WeatherCode;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class WeatherCodeTest extends TestCase
{
    public function testDefinedCodesAreReturnedDirectly(): void
    {
        $this->assertSame(
            WeatherCode::ClearSky,
            WeatherCode::fromInt(0)
        );

        $this->assertSame(
            WeatherCode::ThunderstormHailHeavy,
            WeatherCode::fromInt(99)
        );

        $this->assertSame(
            WeatherCode::Fog,
            WeatherCode::fromInt(45)
        );
    }

    #[DataProvider('undefinedPrecipitationProvider')]
    public function testUndefinedPrecipitationRange(int $code): void
    {
        $this->assertSame(
            WeatherCode::UndefinedPrecipitation,
            WeatherCode::fromInt($code)
        );
    }

    public static function undefinedPrecipitationProvider(): array
    {
        return [
            'lower bound' => [4],
            'middle' => [20],
            'upper bound' => [44],
        ];
    }

    #[DataProvider('undefinedDrizzleProvider')]
    public function testUndefinedDrizzle(int $code): void
    {
        $this->assertSame(
            WeatherCode::UndefinedDrizzle,
            WeatherCode::fromInt($code)
        );
    }

    public static function undefinedDrizzleProvider(): array
    {
        return [
            [52],
            [54],
        ];
    }

    #[DataProvider('undefinedRainProvider')]
    public function testUndefinedRain(int $code): void
    {
        $this->assertSame(
            WeatherCode::UndefinedRain,
            WeatherCode::fromInt($code)
        );
    }

    public static function undefinedRainProvider(): array
    {
        return [
            [62],
            [64],
        ];
    }

    #[DataProvider('undefinedSnowProvider')]
    public function testUndefinedSnow(int $code): void
    {
        $this->assertSame(
            WeatherCode::UndefinedSnow,
            WeatherCode::fromInt($code)
        );
    }

    public static function undefinedSnowProvider(): array
    {
        return [
            [72],
            [74],
            [76],
        ];
    }

    #[DataProvider('undefinedThunderstormProvider')]
    public function testUndefinedThunderstorm(int $code): void
    {
        $this->assertSame(
            WeatherCode::UndefinedThunderstorm,
            WeatherCode::fromInt($code)
        );
    }

    public static function undefinedThunderstormProvider(): array
    {
        return [
            [97],
            [98],
        ];
    }

    public function testUnknownCodeFallsBackToUndefined(): void
    {
        $this->assertSame(
            WeatherCode::Undefined,
            WeatherCode::fromInt(500)
        );

        $this->assertSame(
            WeatherCode::Undefined,
            WeatherCode::fromInt(-999)
        );
    }

    public function testIsDefinedReturnsTrueForNonNegativeValues(): void
    {
        $this->assertTrue(WeatherCode::ClearSky->isDefined());
        $this->assertTrue(WeatherCode::Thunderstorm->isDefined());
    }

    public function testIsDefinedReturnsFalseForNegativeValues(): void
    {
        $this->assertFalse(WeatherCode::Undefined->isDefined());
        $this->assertFalse(WeatherCode::UndefinedRain->isDefined());
        $this->assertFalse(WeatherCode::UndefinedSnow->isDefined());
    }

    public function testEnumCasesContainNoDuplicateValues(): void
    {
        $values = array_map(
            static fn(WeatherCode $case) => $case->value,
            WeatherCode::cases()
        );

        $this->assertSame(
            $values,
            array_values(array_unique($values))
        );
    }
}