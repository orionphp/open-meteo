<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Unit\Response\Hourly;

use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Response\Hourly\HourlyData;
use Orionphp\OpenMeteo\Response\Hourly\HourlyFieldData;
use PHPUnit\Framework\TestCase;

final class HourlyDataTest extends TestCase
{
    public function testTimeReturnsGivenList(): void
    {
        $data = new HourlyData(
            ['2025-01-01T00:00', '2025-01-01T01:00'],
            []
        );

        $this->assertSame(
            ['2025-01-01T00:00', '2025-01-01T01:00'],
            $data->time()
        );
    }

    public function testFieldReturnsExistingField(): void
    {
        $fieldData = new HourlyFieldData(
            field: HourlyField::TEMPERATURE_2M,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [10, 11],
            ]
        );

        $data = new HourlyData(
            ['2025-01-01T00:00', '2025-01-01T01:00'],
            [
                HourlyField::TEMPERATURE_2M->value => $fieldData,
            ]
        );

        $this->assertSame(
            $fieldData,
            $data->field(HourlyField::TEMPERATURE_2M)
        );
    }

    public function testFieldReturnsNullIfFieldIsMissing(): void
    {
        $data = new HourlyData(
            ['2025-01-01T00:00'],
            []
        );

        $this->assertNull(
            $data->field(HourlyField::TEMPERATURE_2M)
        );
    }

    public function testAvailableFieldsReturnsCorrectEnums(): void
    {
        $fieldData = new HourlyFieldData(
            field: HourlyField::TEMPERATURE_2M,
            unit: '°C',
            modelValues: [
                WeatherModel::GFS->value => [10],
            ]
        );

        $data = new HourlyData(
            ['2025-01-01T00:00'],
            [
                HourlyField::TEMPERATURE_2M->value => $fieldData,
            ]
        );

        $this->assertSame(
            [HourlyField::TEMPERATURE_2M],
            $data->availableFields()
        );
    }
}
