<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Parser;

use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Parser\CurrentParser;
use Orionphp\OpenMeteo\Response\Current\CurrentData;
use PHPUnit\Framework\TestCase;

final class CurrentParserTest extends TestCase
{
    public function testParseReturnsNullIfSectionIsNull(): void
    {
        $this->assertNull(CurrentParser::parse(null, null));
    }

    public function testParseReturnsCurrentDataWithValidFields(): void
    {
        $section = [
            'time' => '2025-01-01T00:00',
            'temperature_2m' => 12.5,
            'weathercode' => 0,
        ];

        $units = [
            'temperature_2m' => '°C',
            'weathercode' => 'wmo',
        ];

        $result = CurrentParser::parse($section, $units);

        $this->assertInstanceOf(CurrentData::class, $result);
        $this->assertSame('2025-01-01T00:00', $result->time());

        $temperature = $result->field(CurrentField::TEMPERATURE_2M);

        $this->assertNotNull($temperature);
        $this->assertSame(CurrentField::TEMPERATURE_2M, $temperature->field());
        $this->assertSame(12.5, $temperature->value());
        $this->assertSame('°C', $temperature->unit());

        $weather = $result->field(CurrentField::WEATHER_CODE);

        $this->assertNotNull($weather);
        $this->assertSame(0, $weather->value());
        $this->assertSame('wmo', $weather->unit());
    }

    public function testUnknownFieldsAreIgnored(): void
    {
        $section = [
            'time' => '2025-01-01T00:00',
            'unknown_field' => 123,
        ];

        $result = CurrentParser::parse($section, null);

        $this->assertInstanceOf(CurrentData::class, $result);
        $this->assertSame([], $result->availableFields());
    }

    public function testNonScalarValuesAreIgnored(): void
    {
        $section = [
            'time' => '2025-01-01T00:00',
            'temperature_2m' => ['invalid'],
        ];

        $result = CurrentParser::parse($section, null);

        $this->assertInstanceOf(CurrentData::class, $result);

        $this->assertNull(
            $result->field(CurrentField::TEMPERATURE_2M)
        );
    }

    public function testNullValueIsAllowed(): void
    {
        $section = [
            'time' => '2025-01-01T00:00',
            'temperature_2m' => null,
        ];

        $result = CurrentParser::parse($section, null);

        $this->assertInstanceOf(CurrentData::class, $result);

        $field = $result->field(CurrentField::TEMPERATURE_2M);

        $this->assertNotNull($field);
        $this->assertNull($field->value());
    }

    public function testUnitIsNullIfUnitsArrayIsNull(): void
    {
        $section = [
            'time' => '2025-01-01T00:00',
            'temperature_2m' => 10,
        ];

        $result = CurrentParser::parse($section, null);

        $this->assertInstanceOf(CurrentData::class, $result);

        $field = $result->field(CurrentField::TEMPERATURE_2M);

        $this->assertNotNull($field);
        $this->assertNull($field->unit());
    }

    public function testUnitIsIgnoredIfNotString(): void
    {
        $section = [
            'time' => '2025-01-01T00:00',
            'temperature_2m' => 10,
        ];

        $units = [
            'temperature_2m' => 123,
        ];

        $result = CurrentParser::parse($section, $units);

        $this->assertInstanceOf(CurrentData::class, $result);

        $field = $result->field(CurrentField::TEMPERATURE_2M);

        $this->assertNotNull($field);
        $this->assertNull($field->unit());
    }

    public function testUnitIsSetIfValidString(): void
    {
        $section = [
            'time' => '2025-01-01T00:00',
            'temperature_2m' => 10,
        ];

        $units = [
            'temperature_2m' => '°C',
        ];

        $result = CurrentParser::parse($section, $units);

        $this->assertInstanceOf(CurrentData::class, $result);

        $field = $result->field(CurrentField::TEMPERATURE_2M);

        $this->assertNotNull($field);
        $this->assertSame('°C', $field->unit());
    }
}
