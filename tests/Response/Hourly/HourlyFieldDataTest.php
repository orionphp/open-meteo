<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Response\Hourly;

use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Response\Hourly\HourlyFieldData;
use PHPUnit\Framework\TestCase;

final class HourlyFieldDataTest extends TestCase
{
    public function testConstructorAssignsValues(): void
    {
        $data = new HourlyFieldData(
            field: HourlyField::TEMPERATURE_2M,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [10, 11],
            ]
        );

        $this->assertSame(HourlyField::TEMPERATURE_2M, $data->field());
        $this->assertSame('°C', $data->unit());
    }

    public function testValuesReturnsValuesForModel(): void
    {
        $data = new HourlyFieldData(
            field: HourlyField::TEMPERATURE_2M,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [10, 11],
                WeatherModel::ICON_GLOBAL->value => [12, 13],
            ]
        );

        $this->assertSame(
            [10, 11],
            $data->values(WeatherModel::GFS)
        );

        $this->assertSame(
            [12, 13],
            $data->values(WeatherModel::ICON_GLOBAL)
        );
    }

    public function testValuesReturnsEmptyArrayForUnknownModel(): void
    {
        $data = new HourlyFieldData(
            field: HourlyField::TEMPERATURE_2M,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [10],
            ]
        );

        $this->assertSame(
            [],
            $data->values(WeatherModel::ICON_GLOBAL)
        );
    }

    public function testModelsReturnsCorrectEnums(): void
    {
        $data = new HourlyFieldData(
            field: HourlyField::TEMPERATURE_2M,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [10],
                WeatherModel::ICON_GLOBAL->value => [12],
            ]
        );

        $this->assertSame(
            [
                WeatherModel::GFS,
                WeatherModel::ICON_GLOBAL,
            ],
            $data->models()
        );
    }
}
