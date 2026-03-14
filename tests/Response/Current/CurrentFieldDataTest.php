<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Response\Current;

use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Response\Current\CurrentFieldData;
use PHPUnit\Framework\TestCase;

final class CurrentFieldDataTest extends TestCase
{
    public function testConstructorAssignsValues(): void
    {
        $data = new CurrentFieldData(
            field: CurrentField::TEMPERATURE_2M,
            unit: '°C',
            value: 10
        );

        $this->assertSame(CurrentField::TEMPERATURE_2M, $data->field());
        $this->assertSame('°C', $data->unit());
        $this->assertSame(10, $data->value());
    }

    public function testUnitCanBeNull(): void
    {
        $data = new CurrentFieldData(
            field: CurrentField::TEMPERATURE_2M,
            unit: null,
            value: 10
        );

        $this->assertNull($data->unit());
    }

    public function testValueCanBeNull(): void
    {
        $data = new CurrentFieldData(
            field: CurrentField::TEMPERATURE_2M,
            unit: '°C',
            value: null
        );

        $this->assertNull($data->value());
    }

    public function testValueCanBeString(): void
    {
        $data = new CurrentFieldData(
            field: CurrentField::WEATHER_CODE,
            unit: null,
            value: 'rain'
        );

        $this->assertSame('rain', $data->value());
    }
}