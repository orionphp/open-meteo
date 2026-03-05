<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Enum;

use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Exception\InvalidWeatherModelException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class WeatherModelTest extends TestCase
{
    public function testItHasExpectedNumberOfCases(): void
    {
        $this->assertCount(15, WeatherModel::cases());
    }

    public function testValuesReturnsAllBackedValues(): void
    {
        $this->assertSame(
            array_map(
                static fn(WeatherModel $case) => $case->value,
                WeatherModel::cases()
            ),
            WeatherModel::values()
        );
    }

    public function testNamesReturnsAllCaseNames(): void
    {
        $this->assertSame(
            array_map(
                static fn(WeatherModel $case) => $case->name,
                WeatherModel::cases()
            ),
            WeatherModel::names()
        );
    }

    #[DataProvider('validModelProvider')]
    public function testFromStringReturnsEnum(string $value, WeatherModel $expected): void
    {
        $this->assertSame($expected, WeatherModel::fromString($value));
    }

    public static function validModelProvider(): array
    {
        return array_map(
            static fn(WeatherModel $case) => [$case->value, $case],
            WeatherModel::cases()
        );
    }

    public function testFromStringThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(InvalidWeatherModelException::class);
        $this->expectExceptionMessage('Unsupported weather model');

        WeatherModel::fromString('invalid_model');
    }

    #[DataProvider('globalProvider')]
    public function testIsGlobal(WeatherModel $model): void
    {
        $this->assertTrue($model->isGlobal());
        $this->assertFalse($model->isEuropean());
        $this->assertFalse($model->isUnitedStates());
    }

    public static function globalProvider(): array
    {
        return [
            [WeatherModel::ECMWF_IFS],
            [WeatherModel::GFS],
            [WeatherModel::ICON_GLOBAL],
            [WeatherModel::GEM_GLOBAL],
            [WeatherModel::JMA_GSM],
            [WeatherModel::UKMO_GLOBAL],
        ];
    }

    #[DataProvider('europeProvider')]
    public function testIsEuropean(WeatherModel $model): void
    {
        $this->assertTrue($model->isEuropean());
        $this->assertFalse($model->isGlobal());
        $this->assertFalse($model->isUnitedStates());
    }

    public static function europeProvider(): array
    {
        return [
            [WeatherModel::ICON_EU],
            [WeatherModel::ICON_D2],
            [WeatherModel::METEOFRANCE_ARPEGE],
            [WeatherModel::METEOFRANCE_AROME],
            [WeatherModel::DMI_HARMONIE_AROME_EUROPE],
            [WeatherModel::KNMI_HARMONIE_AROME_NETHERLANDS],
        ];
    }

    #[DataProvider('usProvider')]
    public function testIsUnitedStates(WeatherModel $model): void
    {
        $this->assertTrue($model->isUnitedStates());
        $this->assertFalse($model->isGlobal());
        $this->assertFalse($model->isEuropean());
    }

    public static function usProvider(): array
    {
        return [
            [WeatherModel::HRRR],
            [WeatherModel::NAM_CONUS],
        ];
    }

    public function testEnumValuesContainNoDuplicates(): void
    {
        $values = WeatherModel::values();

        $this->assertSame(
            $values,
            array_values(array_unique($values))
        );
    }

    public function testToQueryBuildsCorrectString(): void
    {
        $query = WeatherModel::toQuery([
            WeatherModel::ICON_D2,
            WeatherModel::GFS,
        ]);

        $this->assertSame('icon_d2,gfs', $query);
    }

    public function testRecommendedForReturnsModels(): void
    {
        $models = WeatherModel::recommendedFor(52.52, 13.41); // Berlin

        $this->assertNotEmpty($models);
        $this->assertContains(WeatherModel::ECMWF_IFS, $models);
        $this->assertContains(WeatherModel::GFS, $models);
    }

    public function testBestForReturnsFirstRecommendedModel(): void
    {
        $best = WeatherModel::bestFor(52.52, 13.41);

        $this->assertInstanceOf(WeatherModel::class, $best);
    }
}