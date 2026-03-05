<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Enum;

use Orionphp\OpenMeteo\Exception\InvalidWeatherModelException;

use function array_map;
use function array_unique;
use function implode;
use function in_array;
use function sprintf;
use function trim;

/**
 * Represents Open-Meteo weather forecast models.
 *
 * Models can be combined in API requests:
 * models=icon_d2,gfs
 *
 * @see https://open-meteo.com/en/docs
 */
enum WeatherModel: string
{
    use BackedEnumValuesTrait;

    /**
     * Open-Meteo recommended automatic model selection.
     */
    case BEST_MATCH = 'best_match';

    // Global models
    case ECMWF_IFS = 'ecmwf_ifs';
    case GFS = 'gfs';
    case ICON_GLOBAL = 'icon_global';
    case GEM_GLOBAL = 'gem_global';
    case JMA_GSM = 'jma_gsm';
    case UKMO_GLOBAL = 'ukmo_global';

    // Europe / Regional models
    case ICON_EU = 'icon_eu';
    case ICON_D2 = 'icon_d2';
    case METEOFRANCE_ARPEGE = 'meteofrance_arpege';
    case METEOFRANCE_AROME = 'meteofrance_arome';
    case DMI_HARMONIE_AROME_EUROPE = 'dmi_harmonie_arome_europe';
    case KNMI_HARMONIE_AROME_NETHERLANDS = 'knmi_harmonie_arome_netherlands';

    // United States models
    case HRRR = 'hrrr';
    case NAM_CONUS = 'nam_conus';

    private const array GLOBAL = [
        self::ECMWF_IFS,
        self::GFS,
        self::ICON_GLOBAL,
        self::GEM_GLOBAL,
        self::JMA_GSM,
        self::UKMO_GLOBAL,
    ];

    private const array EUROPE = [
        self::ICON_EU,
        self::ICON_D2,
        self::METEOFRANCE_ARPEGE,
        self::METEOFRANCE_AROME,
        self::DMI_HARMONIE_AROME_EUROPE,
        self::KNMI_HARMONIE_AROME_NETHERLANDS,
    ];

    private const array UNITED_STATES = [
        self::HRRR,
        self::NAM_CONUS,
    ];

    /**
     * Create enum from string value.
     */
    public static function fromString(string $value): self
    {
        $model = self::tryFrom(trim($value));

        if ($model === null) {
            throw new InvalidWeatherModelException(
                sprintf('Unsupported weather model "%s".', $value)
            );
        }

        return $model;
    }

    /**
     * Whether the model is a global forecast model.
     */
    public function isGlobal(): bool
    {
        return in_array($this, self::GLOBAL, true);
    }

    /**
     * Whether the model is a European regional model.
     */
    public function isEuropean(): bool
    {
        return in_array($this, self::EUROPE, true);
    }

    /**
     * Whether the model is a United States regional model.
     */
    public function isUnitedStates(): bool
    {
        return in_array($this, self::UNITED_STATES, true);
    }

    /**
     * Whether the model is a regional model.
     */
    public function isRegional(): bool
    {
        return $this->isEuropean() || $this->isUnitedStates();
    }

    /**
     * Convert models to Open-Meteo API query string.
     *
     * @param list<self> $models
     */
    public static function toQuery(array $models): string
    {
        return implode(',', array_map(
            static fn(self $model) => $model->value,
            $models
        ));
    }

    /**
     * Returns recommended models ordered by precision for a given coordinate.
     *
     * @return list<self>
     */
    public static function recommendedFor(float $lat, float $lon): array
    {
        $models = [];

        // Germany (high resolution ICON-D2)
        if (self::inBounds($lat, $lon, 47, 55, 5, 16)) {
            $models[] = self::ICON_D2;
        }

        // France high resolution
        if (self::inBounds($lat, $lon, 41, 51, -5, 9)) {
            $models[] = self::METEOFRANCE_AROME;
        }

        // Europe regional models
        if (self::inBounds($lat, $lon, 34, 72, -25, 45)) {
            $models[] = self::ICON_EU;
            $models[] = self::METEOFRANCE_ARPEGE;
        }

        // United States
        if (self::inBounds($lat, $lon, 24, 50, -125, -66)) {
            $models[] = self::HRRR;
            $models[] = self::NAM_CONUS;
        }

        // Global fallback
        $models[] = self::ECMWF_IFS;
        $models[] = self::GFS;

        $unique = [];

        foreach ($models as $model) {
            $unique[$model->value] = $model;
        }

        return array_values($unique);
    }

    /**
     * Returns the best model for a given coordinate.
     */
    public static function bestFor(float $lat, float $lon): self
    {
        return self::recommendedFor($lat, $lon)[0];
    }

    /**
     * Checks whether coordinates are within a bounding box.
     */
    private static function inBounds(
        float $lat,
        float $lon,
        float $minLat,
        float $maxLat,
        float $minLon,
        float $maxLon
    ): bool {
        return $lat >= $minLat
            && $lat <= $maxLat
            && $lon >= $minLon
            && $lon <= $maxLon;
    }
}