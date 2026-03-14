<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Response\Minutely15;

use Orionphp\OpenMeteo\Enum\Minutely15Field;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Response\Minutely15\Minutely15Data;
use Orionphp\OpenMeteo\Response\Minutely15\Minutely15FieldData;
use PHPUnit\Framework\TestCase;

final class Minutely15DataTest extends TestCase
{
    public function testTimeReturnsGivenList(): void
    {
        $data = new Minutely15Data(
            ['2025-01-01T00:00', '2025-01-01T00:15'],
            []
        );

        $this->assertSame(
            ['2025-01-01T00:00', '2025-01-01T00:15'],
            $data->time()
        );
    }

    public function testFieldReturnsExistingField(): void
    {
        $fieldData = new Minutely15FieldData(
            field: Minutely15Field::TEMPERATURE_2M,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [10, 11],
            ]
        );

        $data = new Minutely15Data(
            ['2025-01-01T00:00', '2025-01-01T00:15'],
            [
                Minutely15Field::TEMPERATURE_2M->value => $fieldData,
            ]
        );

        $this->assertSame(
            $fieldData,
            $data->field(Minutely15Field::TEMPERATURE_2M)
        );
    }

    public function testFieldReturnsNullIfFieldIsMissing(): void
    {
        $data = new Minutely15Data(
            ['2025-01-01T00:00'],
            []
        );

        $this->assertNull(
            $data->field(Minutely15Field::TEMPERATURE_2M)
        );
    }

    public function testAvailableFieldsReturnsCorrectEnums(): void
    {
        $fieldData = new Minutely15FieldData(
            field: Minutely15Field::TEMPERATURE_2M,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [10],
            ]
        );

        $data = new Minutely15Data(
            ['2025-01-01T00:00'],
            [
                Minutely15Field::TEMPERATURE_2M->value => $fieldData,
            ]
        );

        $this->assertSame(
            [Minutely15Field::TEMPERATURE_2M],
            $data->availableFields()
        );
    }
}