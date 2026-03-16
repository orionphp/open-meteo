<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Response\Minutely15;

use Orionphp\OpenMeteo\Enum\Minutely15Field;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Response\Minutely15\Minutely15FieldData;
use PHPUnit\Framework\TestCase;

final class Minutely15FieldDataTest extends TestCase
{
    public function testConstructorAssignsValues(): void
    {
        $data = new Minutely15FieldData(
            field: Minutely15Field::TEMPERATURE_2M,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [10, 11],
            ]
        );

        $this->assertSame(Minutely15Field::TEMPERATURE_2M, $data->field());
        $this->assertSame('°C', $data->unit());
    }

    public function testValuesReturnsValuesForModel(): void
    {
        $data = new Minutely15FieldData(
            field: Minutely15Field::TEMPERATURE_2M,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [10, 11],
                WeatherModel::ICON_GLOBAL->value => [12, 13],
            ]
        );

        $this->assertSame([10, 11], $data->values(WeatherModel::GFS));
        $this->assertSame([12, 13], $data->values(WeatherModel::ICON_GLOBAL));
    }

    public function testValuesReturnsEmptyArrayForUnknownModel(): void
    {
        $data = new Minutely15FieldData(
            field: Minutely15Field::TEMPERATURE_2M,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [10],
            ]
        );

        $this->assertSame([], $data->values(WeatherModel::ICON_GLOBAL));
    }

    public function testModelsReturnsCorrectEnums(): void
    {
        $data = new Minutely15FieldData(
            field: Minutely15Field::TEMPERATURE_2M,
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
