<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Response\Current;

use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Response\Current\CurrentData;
use Orionphp\OpenMeteo\Response\Current\CurrentFieldData;
use PHPUnit\Framework\TestCase;

final class CurrentDataTest extends TestCase
{
    public function testTimeReturnsGivenTime(): void
    {
        $data = new CurrentData(
            '2025-01-01T00:00',
            []
        );

        $this->assertSame(
            '2025-01-01T00:00',
            $data->time()
        );
    }

    public function testFieldReturnsExistingField(): void
    {
        $fieldData = new CurrentFieldData(
            field: CurrentField::TEMPERATURE_2M,
            unit: '°C',
            value: 10
        );

        $data = new CurrentData(
            '2025-01-01T00:00',
            [
                CurrentField::TEMPERATURE_2M->value => $fieldData,
            ]
        );

        $this->assertSame(
            $fieldData,
            $data->field(CurrentField::TEMPERATURE_2M)
        );
    }

    public function testFieldReturnsNullIfFieldDoesNotExist(): void
    {
        $data = new CurrentData(
            '2025-01-01T00:00',
            []
        );

        $this->assertNull(
            $data->field(CurrentField::TEMPERATURE_2M)
        );
    }

    public function testAvailableFieldsReturnsCorrectEnums(): void
    {
        $fieldData = new CurrentFieldData(
            field: CurrentField::TEMPERATURE_2M,
            unit: '°C',
            value: 10
        );

        $data = new CurrentData(
            '2025-01-01T00:00',
            [
                CurrentField::TEMPERATURE_2M->value => $fieldData,
            ]
        );

        $this->assertSame(
            [CurrentField::TEMPERATURE_2M],
            $data->availableFields()
        );
    }
}
