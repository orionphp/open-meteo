<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Request;

use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\Minutely15Field;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Exception\InvalidCoordinatesException;

use function sprintf;

/**
 * Represents a request for weather forecasting data based on geographic coordinates, models, and fields.
 */
final readonly class ForecastRequest
{
    /**
     * @param list<WeatherModel> $models
     * @param list<CurrentField>|null $current
     * @param list<Minutely15Field>|null $minutely15
     * @param list<HourlyField>|null $hourly
     * @param list<DailyField>|null $daily
     */
    public function __construct(
        public float   $latitude,
        public float   $longitude,
        public array   $models,
        public ?string $timezone,
        public ?array  $current = null,
        public ?array  $minutely15 = null,
        public ?array  $hourly = null,
        public ?array  $daily = null
    ) {
        if ($latitude < -90.0 || $latitude > 90.0) {
            throw new InvalidCoordinatesException(
                sprintf('Latitude must be between -90 and 90. "%s" given.', $latitude)
            );
        }

        if ($longitude < -180.0 || $longitude > 180.0) {
            throw new InvalidCoordinatesException(
                sprintf('Longitude must be between -180 and 180. "%s" given.', $longitude)
            );
        }
    }
}
