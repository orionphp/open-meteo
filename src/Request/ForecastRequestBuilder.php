<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Request;

use BackedEnum;
use DateTimeZone;
use Exception;
use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\Locale;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Exception\InvalidCoordinatesException;
use Orionphp\OpenMeteo\Exception\InvalidTimezoneException;
use Orionphp\OpenMeteo\Exception\OpenMeteoException;

/**
 * Builder for creating ForecastRequest objects.
 */
final class ForecastRequestBuilder
{
    private float $latitude;
    private float $longitude;

    /** @var list<WeatherModel> */
    private array $models = [];

    private Locale $locale;

    private ?string $timezone = null;

    /** @var list<CurrentField>|null */
    private ?array $current = null;

    /** @var list<HourlyField>|null */
    private ?array $hourly = null;

    /** @var list<DailyField>|null */
    private ?array $daily = null;

    /**
     * @param float $latitude
     * @param float $longitude
     */
    private function __construct(float $latitude, float $longitude)
    {
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
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->locale = Locale::EN;
    }

    public static function create(float $latitude, float $longitude): self
    {
        return new self($latitude, $longitude);
    }

    public function models(WeatherModel ...$models): self
    {
        if ($models === []) {
            throw new OpenMeteoException('At least one weather model must be provided.');
        }

        $this->models = $this->uniqueEnums(array_values($models));

        return $this;
    }

    public function locale(Locale $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function timezone(string $timezone): self
    {
        try {
            new DateTimeZone($timezone);
        } catch (Exception $e) {
            throw new InvalidTimezoneException($e->getMessage());
        }
        $this->timezone = $timezone;
        return $this;
    }

    public function current(CurrentField ...$fields): self
    {
        if ($fields === []) {
            throw new OpenMeteoException('Current fields cannot be empty.');
        }

        $this->current = $this->uniqueEnums(array_values($fields));

        return $this;
    }

    public function hourly(HourlyField ...$fields): self
    {
        if ($fields === []) {
            throw new OpenMeteoException('Hourly fields cannot be empty.');
        }

        $this->hourly = $this->uniqueEnums(array_values($fields));

        return $this;
    }

    public function daily(DailyField ...$fields): self
    {
        if ($fields === []) {
            throw new OpenMeteoException('Daily fields cannot be empty.');
        }

        $this->daily = $this->uniqueEnums(array_values($fields));

        return $this;
    }

    public function build(): ForecastRequest
    {
        if ($this->models === []) {
            throw new OpenMeteoException('No weather models configured.');
        }

        return new ForecastRequest(
            latitude: $this->latitude,
            longitude: $this->longitude,
            models: $this->models,
            locale: $this->locale,
            timezone: $this->timezone,
            current: $this->current,
            hourly: $this->hourly,
            daily: $this->daily,
        );
    }

    /**
     * @template T of BackedEnum
     * @param list<T> $enums
     * @return list<T>
     */
    private function uniqueEnums(array $enums): array
    {
        $unique = [];

        foreach ($enums as $enum) {
            $unique[$enum->value] = $enum;
        }

        /** @var list<T> */
        return array_values($unique);
    }
}
