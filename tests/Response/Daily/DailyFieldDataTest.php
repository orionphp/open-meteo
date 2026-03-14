<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Response\Daily;

use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Response\Daily\DailyFieldData;
use PHPUnit\Framework\TestCase;

final class DailyFieldDataTest extends TestCase
{
    public function testConstructorAssignsValues(): void
    {
        $data = new DailyFieldData(
            field: DailyField::TEMPERATURE_2M_MAX,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [12, 13],
            ]
        );

        $this->assertSame(DailyField::TEMPERATURE_2M_MAX, $data->field());
        $this->assertSame('°C', $data->unit());
    }

    public function testValuesReturnsValuesForModel(): void
    {
        $data = new DailyFieldData(
            field: DailyField::TEMPERATURE_2M_MAX,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [12, 13],
                WeatherModel::ICON_GLOBAL->value => [14, 15],
            ]
        );

        $this->assertSame(
            [12, 13],
            $data->values(WeatherModel::GFS)
        );

        $this->assertSame(
            [14, 15],
            $data->values(WeatherModel::ICON_GLOBAL)
        );
    }

    public function testValuesReturnsEmptyArrayForUnknownModel(): void
    {
        $data = new DailyFieldData(
            field: DailyField::TEMPERATURE_2M_MAX,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [12],
            ]
        );

        $this->assertSame(
            [],
            $data->values(WeatherModel::ICON_GLOBAL)
        );
    }

    public function testModelsReturnsCorrectEnums(): void
    {
        $data = new DailyFieldData(
            field: DailyField::TEMPERATURE_2M_MAX,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [12],
                WeatherModel::ICON_GLOBAL->value => [14],
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