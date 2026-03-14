<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Parser;

use Orionphp\OpenMeteo\Exception\OpenMeteoException;
use Orionphp\OpenMeteo\Parser\ApiStructureValidator;
use PHPUnit\Framework\TestCase;

final class ApiStructureValidatorTest extends TestCase
{
    public function testValidateTimeSeriesAcceptsValidStructure(): void
    {
        $section = [
            'time' => ['2025-01-01', '2025-01-02'],
            'temperature' => [10, 11],
            'precipitation' => [0.1, 0.2],
        ];

        ApiStructureValidator::validateTimeSeries($section);

        $this->assertTrue(true);
    }

    public function testValidateTimeSeriesThrowsIfTimeMissing(): void
    {
        $this->expectException(OpenMeteoException::class);
        $this->expectExceptionMessage('API response missing "time" array.');

        $section = [
            'temperature' => [10, 11],
        ];

        ApiStructureValidator::validateTimeSeries($section);
    }

    public function testValidateTimeSeriesThrowsIfTimeNotArray(): void
    {
        $this->expectException(OpenMeteoException::class);
        $this->expectExceptionMessage('API response missing "time" array.');

        $section = [
            'time' => 'invalid',
            'temperature' => [10, 11],
        ];

        ApiStructureValidator::validateTimeSeries($section);
    }

    public function testValidateTimeSeriesThrowsIfTimeContainsNonString(): void
    {
        $this->expectException(OpenMeteoException::class);
        $this->expectExceptionMessage('Invalid time value in API response.');

        $section = [
            'time' => ['2025-01-01', 123],
            'temperature' => [10, 11],
        ];

        ApiStructureValidator::validateTimeSeries($section);
    }

    public function testValidateTimeSeriesThrowsIfFieldNotArray(): void
    {
        $this->expectException(OpenMeteoException::class);
        $this->expectExceptionMessage('Field "temperature" must be array.');

        $section = [
            'time' => ['2025-01-01', '2025-01-02'],
            'temperature' => 10,
        ];

        ApiStructureValidator::validateTimeSeries($section);
    }

    public function testValidateTimeSeriesThrowsIfLengthMismatch(): void
    {
        $this->expectException(OpenMeteoException::class);
        $this->expectExceptionMessage('Length mismatch');

        $section = [
            'time' => ['2025-01-01', '2025-01-02'],
            'temperature' => [10],
        ];

        ApiStructureValidator::validateTimeSeries($section);
    }

    public function testValidateSingleTimeAcceptsValidTime(): void
    {
        $section = [
            'time' => '2025-01-01T00:00',
            'temperature' => 10,
        ];

        ApiStructureValidator::validateSingleTime($section);

        $this->assertTrue(true);
    }

    public function testValidateSingleTimeAcceptsMissingTime(): void
    {
        $section = [
            'temperature' => 10,
        ];

        ApiStructureValidator::validateSingleTime($section);

        $this->assertTrue(true);
    }

    public function testValidateSingleTimeThrowsIfTimeNotString(): void
    {
        $this->expectException(OpenMeteoException::class);
        $this->expectExceptionMessage('API response "time" must be string.');

        $section = [
            'time' => ['invalid'],
        ];

        ApiStructureValidator::validateSingleTime($section);
    }
}