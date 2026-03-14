<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Request;

use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\Locale;
use Orionphp\OpenMeteo\Enum\Minutely15Field;
use Orionphp\OpenMeteo\Enum\WeatherModel;

/**
 * Represents a request for weather forecasting data based on geographic coordinates, models, and fields.
 *
 * This class encapsulates the necessary input parameters to retrieve weather
 * forecasting data, including latitude, longitude, weather models, display
 * preferences, and optional specific fields for current, 15-minute, hourly, and daily data.
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
        public Locale  $locale,
        public ?string $timezone,
        public ?array  $current = null,
        public ?array  $minutely15 = null,
        public ?array  $hourly = null,
        public ?array  $daily = null
    ) {
    }
}
