<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Response\Daily;

use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Response\Daily\DailyData;
use Orionphp\OpenMeteo\Response\Daily\DailyFieldData;
use PHPUnit\Framework\TestCase;

final class DailyDataTest extends TestCase
{
    public function testTimeReturnsGivenList(): void
    {
        $data = new DailyData(
            ['2025-01-01', '2025-01-02'],
            []
        );

        $this->assertSame(
            ['2025-01-01', '2025-01-02'],
            $data->time()
        );
    }

    public function testFieldReturnsExistingField(): void
    {
        $fieldData = new DailyFieldData(
            field: DailyField::TEMPERATURE_2M_MAX,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [12, 13],
            ]
        );

        $data = new DailyData(
            ['2025-01-01', '2025-01-02'],
            [
                DailyField::TEMPERATURE_2M_MAX->value => $fieldData,
            ]
        );

        $this->assertSame(
            $fieldData,
            $data->field(DailyField::TEMPERATURE_2M_MAX)
        );
    }

    public function testFieldReturnsNullIfFieldIsMissing(): void
    {
        $data = new DailyData(
            ['2025-01-01'],
            []
        );

        $this->assertNull(
            $data->field(DailyField::TEMPERATURE_2M_MAX)
        );
    }

    public function testAvailableFieldsReturnsCorrectEnums(): void
    {
        $fieldData = new DailyFieldData(
            field: DailyField::TEMPERATURE_2M_MAX,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [12],
            ]
        );

        $data = new DailyData(
            ['2025-01-01'],
            [
                DailyField::TEMPERATURE_2M_MAX->value => $fieldData,
            ]
        );

        $this->assertSame(
            [DailyField::TEMPERATURE_2M_MAX],
            $data->availableFields()
        );
    }
}
