# Orionphp Open-Meteo Client

[![CI](https://github.com/orionphp/open-meteo/actions/workflows/ci.yml/badge.svg)](https://github.com/orionphp/open-meteo/actions)
[![Latest Stable Version](https://poser.pugx.org/orionphp/open-meteo/v/stable)](https://packagist.org/packages/orionphp/open-meteo)
[![PHP Version Require](https://poser.pugx.org/orionphp/open-meteo/require/php)](https://packagist.org/packages/orionphp/open-meteo)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

A typed PHP 8.4 client for the [Open-Meteo](https://open-meteo.com/) weather API — free, no API key required.

Supports:
- current conditions
- 15-minute intervals
- hourly forecasts
- daily forecasts
- 14+ global and regional weather models

---

## Requirements

- PHP **8.4+**
- A [PSR-18](https://www.php-fig.org/psr/psr-18/) HTTP client (e.g. Guzzle, Symfony HttpClient)
- A [PSR-17](https://www.php-fig.org/psr/psr-17/) request factory (e.g. `nyholm/psr7`)

---

## Installation

```bash
composer require orionphp/open-meteo
```

You also need a PSR-18 client and a PSR-17 factory. If you don't have one yet:

```bash
composer require guzzlehttp/guzzle nyholm/psr7
```

---

## Quick Start

```php
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Orionphp\OpenMeteo\OpenMeteoClient;
use Orionphp\OpenMeteo\Request\ForecastRequestBuilder;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Enum\HourlyField;

$httpFactory = new HttpFactory();
$client = new OpenMeteoClient(
    httpClient: new Client(),
    requestFactory: $httpFactory,
);

$request = ForecastRequestBuilder::create(52.52, 13.41)         // Berlin coordinates
    ->models(WeatherModel::ICON_D2)     // required: at least one model
    ->timezone('Europe/Berlin')
    ->current(CurrentField::TEMPERATURE_2M, CurrentField::WEATHER_CODE)
    ->hourly(HourlyField::TEMPERATURE_2M, HourlyField::PRECIPITATION)
    ->build();

$forecast = $client->forecast($request);

// Current conditions
$current = $forecast->current;
echo $current->field(CurrentField::TEMPERATURE_2M)->value();   // e.g. 14.3
echo $current->field(CurrentField::TEMPERATURE_2M)->unit();    // °C

// Hourly data
$hourly = $forecast->hourly;
$temps  = $hourly->field(HourlyField::TEMPERATURE_2M)->values(WeatherModel::ICON_D2);
$times  = $hourly->time(); // list of ISO 8601 strings
```

---

## Building a Forecast Request

All requests are created through `ForecastRequestBuilder`. Coordinates are validated on creation — invalid values throw `InvalidCoordinatesException`.

```php
$request = ForecastRequestBuilder::create(float $latitude, float $longitude)
    ->models(WeatherModel ...$models)       // required: at least one model
    ->timezone(string $timezone)            // optional: valid PHP timezone string
    ->current(CurrentField ...$fields)
    ->minutely15(Minutely15Field ...$fields)
    ->hourly(HourlyField ...$fields)
    ->daily(DailyField ...$fields)
    ->build();
```

Every field setter accepts PHP variadic enums. Duplicate entries are deduplicated automatically.

---

## Weather Models

```php
use Orionphp\OpenMeteo\Enum\WeatherModel;
```

| Constant | Value | Region |
|---|---|---|
| `BEST_MATCH` | `best_match` | Auto-select (recommended) |
| `ECMWF_IFS` | `ecmwf_ifs` | Global |
| `GFS` | `gfs` | Global |
| `ICON_GLOBAL` | `icon_global` | Global |
| `GEM_GLOBAL` | `gem_global` | Global |
| `JMA_GSM` | `jma_gsm` | Global |
| `UKMO_GLOBAL` | `ukmo_global` | Global |
| `ICON_EU` | `icon_eu` | Europe |
| `ICON_D2` | `icon_d2` | Germany / Central Europe |
| `METEOFRANCE_ARPEGE` | `meteofrance_arpege` | Europe |
| `METEOFRANCE_AROME` | `meteofrance_arome` | France |
| `DMI_HARMONIE_AROME_EUROPE` | `dmi_harmonie_arome_europe` | Europe |
| `KNMI_HARMONIE_AROME_NETHERLANDS` | `knmi_harmonie_arome_netherlands` | Netherlands |
| `HRRR` | `hrrr` | United States |
| `NAM_CONUS` | `nam_conus` | United States |

### Model helpers

```php
// Automatically select the most precise models for a coordinate
$models = WeatherModel::recommendedFor(52.52, 13.41);

// Get only the single best model
$model = WeatherModel::bestFor(52.52, 13.41);

// Introspect a model
WeatherModel::ICON_D2->isGlobal();    // false
WeatherModel::ICON_D2->isEuropean();  // true
WeatherModel::ICON_D2->isRegional();  // true
```

### Combining multiple models

When you request more than one model, each field in the response carries per-model value arrays:

```php
$request = ForecastRequestBuilder::create(48.85, 2.35)   // Paris
    ->models(WeatherModel::METEOFRANCE_AROME, WeatherModel::ECMWF_IFS)
    ->hourly(HourlyField::TEMPERATURE_2M)
    ->build();

$forecast = $client->forecast($request);
$field    = $forecast->hourly->field(HourlyField::TEMPERATURE_2M);

$aromeTemps = $field->values(WeatherModel::METEOFRANCE_AROME);
$ecmwfTemps = $field->values(WeatherModel::ECMWF_IFS);
```

---

## Forecast Intervals

### Current conditions

```php
use Orionphp\OpenMeteo\Enum\CurrentField;

// Available fields
CurrentField::TEMPERATURE_2M
CurrentField::APPARENT_TEMPERATURE
CurrentField::WEATHER_CODE
CurrentField::RELATIVE_HUMIDITY_2M
CurrentField::WIND_SPEED_10M
CurrentField::WIND_DIRECTION_10M
CurrentField::WIND_GUSTS_10M
CurrentField::PRECIPITATION
CurrentField::RAIN
CurrentField::SNOWFALL
CurrentField::SNOW_DEPTH
CurrentField::CLOUD_COVER
CurrentField::PRESSURE_MSL
CurrentField::SURFACE_PRESSURE
CurrentField::VISIBILITY
CurrentField::UV_INDEX
CurrentField::DEW_POINT_2M
CurrentField::IS_DAY
CurrentField::PRECIPITATION_PROBABILITY
CurrentField::SHOWERS

// Usage
$current = $forecast->current;          // ?CurrentData
$current->time();                        // e.g. "2026-03-17T12:00"
$data = $current->field(CurrentField::TEMPERATURE_2M);
$data->value();                          // float|int|string|null
$data->unit();                           // e.g. "°C"
```

### 15-minute intervals

```php
use Orionphp\OpenMeteo\Enum\Minutely15Field;

$minutely = $forecast->minutely15;       // ?Minutely15Data
$minutely->time();                       // list<string>
$minutely->field(Minutely15Field::PRECIPITATION)->values(WeatherModel::ICON_D2);
```

Available fields: `TEMPERATURE_2M`, `APPARENT_TEMPERATURE`, `RELATIVE_HUMIDITY_2M`, `DEW_POINT_2M`, `PRECIPITATION`, `RAIN`, `SNOWFALL`, `SHOWERS`, `WEATHER_CODE`, `CLOUD_COVER`, `WIND_SPEED_10M`, `WIND_DIRECTION_10M`, `WIND_GUSTS_10M`, `VISIBILITY`, `SHORTWAVE_RADIATION`, `DIRECT_RADIATION`, `DIFFUSE_RADIATION`, `DIRECT_NORMAL_IRRADIANCE`

### Hourly

```php
use Orionphp\OpenMeteo\Enum\HourlyField;

$hourly = $forecast->hourly;             // ?HourlyData
$hourly->time();                         // list<string>
$hourly->field(HourlyField::UV_INDEX)->values(WeatherModel::ECMWF_IFS);
$hourly->availableFields();              // list<HourlyField>
```

Available fields include: `TEMPERATURE_2M`, `APPARENT_TEMPERATURE`, `WEATHER_CODE`, `RELATIVE_HUMIDITY_2M`, `DEW_POINT_2M`, `PRECIPITATION`, `PRECIPITATION_PROBABILITY`, `RAIN`, `SHOWERS`, `SNOWFALL`, `SNOW_DEPTH`, `CLOUD_COVER`, `WIND_SPEED_10M`, `WIND_DIRECTION_10M`, `WIND_GUSTS_10M`, `PRESSURE_MSL`, `SURFACE_PRESSURE`, `VISIBILITY`, `UV_INDEX`, `SHORTWAVE_RADIATION`, `DIRECT_RADIATION`, `DIFFUSE_RADIATION`, `DIRECT_NORMAL_IRRADIANCE`, `EVAPOTRANSPIRATION`, `ET0_FAO_EVAPOTRANSPIRATION`, `VAPOUR_PRESSURE_DEFICIT`, `CAPE`, and soil temperature fields at 0 cm, 6 cm, 18 cm, 54 cm.

### Daily

```php
use Orionphp\OpenMeteo\Enum\DailyField;

$daily = $forecast->daily;               // ?DailyData
$daily->time();                          // list<string> — one entry per day
$daily->field(DailyField::TEMPERATURE_2M_MAX)->values(WeatherModel::ICON_D2);
```

Available fields: `WEATHER_CODE`, `TEMPERATURE_2M_MAX`, `TEMPERATURE_2M_MIN`, `APPARENT_TEMPERATURE_MAX`, `APPARENT_TEMPERATURE_MIN`, `PRECIPITATION_SUM`, `PRECIPITATION_PROBABILITY_MAX`, `RAIN_SUM`, `SHOWERS_SUM`, `SNOWFALL_SUM`, `SNOW_DEPTH_MAX`, `WIND_SPEED_10M_MAX`, `WIND_GUSTS_10M_MAX`, `WIND_DIRECTION_10M_DOMINANT`, `SUNRISE`, `SUNSET`, `SUNSHINE_DURATION`, `UV_INDEX_MAX`, `SHORTWAVE_RADIATION_SUM`, `ET0_FAO_EVAPOTRANSPIRATION`

---

## Weather Code Translations

WMO weather codes returned by the API can be translated to human-readable strings in multiple languages.

```php
use Orionphp\OpenMeteo\Enum\WeatherCode;
use Orionphp\OpenMeteo\Enum\Locale;
use Orionphp\OpenMeteo\Translation\WeatherCodeTranslation;

$translator = new WeatherCodeTranslation();

$code = WeatherCode::fromInt(63); // ModerateRain
$translator->translate($code, Locale::EN); // "Moderate rain"
$translator->translate($code, Locale::DE); // "Mäßiger Regen"
$translator->translate($code, Locale::FR); // "Pluie modérée"
$translator->translate($code, Locale::ES); // "Lluvia moderada"
```

**Supported locales:** `de`, `en`, `es`, `fr`

Translation files are plain PHP arrays. You can supply your own locale directory:

```php
$translator = new WeatherCodeTranslation('/path/to/your/locales');
```

A locale file must return an `array<int, string>` keyed by WMO weather code integer.

---

## Logging

`OpenMeteoClient` accepts any [PSR-3](https://www.php-fig.org/psr/psr-3/) logger as an optional third constructor argument. When omitted, a `NullLogger` is used.

```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('open-meteo');
$logger->pushHandler(new StreamHandler('php://stdout'));

$client = new OpenMeteoClient(
    httpClient: new Client(),
    requestFactory: $httpFactory,
    logger: $logger,
);
```

Logged events:

| Level | Event |
|---|---|
| `info` | Outgoing request URL |
| `error` | HTTP client exception |
| `error` | Non-2xx response status |
| `error` | Invalid or unexpected JSON |

---

## Error Handling

All exceptions extend `OpenMeteoException`. Catch specifically or broadly as needed:

```php
use Orionphp\OpenMeteo\Exception\OpenMeteoException;
use Orionphp\OpenMeteo\Exception\InvalidCoordinatesException;
use Orionphp\OpenMeteo\Exception\InvalidTimezoneException;
use Orionphp\OpenMeteo\Exception\InvalidWeatherModelException;

try {
    $forecast = $client->forecast($request);
} catch (InvalidCoordinatesException $e) {
    // Latitude/longitude out of valid range
} catch (InvalidTimezoneException $e) {
    // Timezone string not recognized by PHP
} catch (InvalidWeatherModelException $e) {
    // WeatherModel::fromString() called with unknown value
} catch (OpenMeteoException $e) {
    // HTTP error, non-2xx status, or malformed JSON response
}
```

---

## Development

```bash
# Install dependencies
composer install

# Run the full check suite (code style + static analysis + tests)
composer check

# Individual tools
composer cs          # Fix code style (PHP-CS-Fixer)
composer cs-check    # Dry-run code style check
composer stan        # PHPStan level 9
composer test        # PHPUnit
```

CI runs automatically on every push and pull request via GitHub Actions.

---

## License

MIT — see [LICENSE](LICENSE) for details.