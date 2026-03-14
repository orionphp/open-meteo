# Orionphp Open-Meteo Client

A lightweight PHP client for the free weather API from
[Open-Meteo](https://open-meteo.com/).

The library provides a clean and type-safe interface for retrieving:

- current weather
- 15-minute forecasts
- hourly forecasts
- daily forecasts

for multiple weather models.

Built for modern PHP with strict typing and a clean domain model.

---

# Features

- PHP **8.4+**
- Strict types
- Enum-based API
- PSR-18 HTTP client support
- PSR-17 request factory support
- PSR-3 logging support
- Multi-model weather forecasts
- Clean response objects
- Fully unit tested

---

# Installation

Install via Composer:

```bash
composer require orionphp/open-meteo
```

# Basic Usage

```php
use Orionphp\OpenMeteo\Client\OpenMeteoClient;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use Orionphp\OpenMeteo\Enum\HourlyField;

$client = new OpenMeteoClient();

$request = new ForecastRequest(
    latitude: 52.52,
    longitude: 13.41,
    hourly: [
        HourlyField::TEMPERATURE_2M
    ]
);

$forecast = $client->forecast($request);

if ($forecast->hasHourly()) {

    $temperature = $forecast
        ->hourly
        ->field(HourlyField::TEMPERATURE_2M)
        ->values()[0];
}
```