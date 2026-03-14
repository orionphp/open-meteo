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

# Basic Usage with Guzzle

```php
use GuzzleHttp\Client;
use Nyholm\Psr7\Factory\Psr17Factory;
use Orionphp\OpenMeteo\Enum\CurrentField;
use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\OpenMeteoClient;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use Psr\Log\NullLogger;

$client = new OpenMeteoClient(new Client(), new Psr17Factory(), new NullLogger());

$request = new ForecastRequest(
    latitude: 51.341,
    longitude: 7.288,
    models: [WeatherModel::BEST_MATCH],
    timezone: 'Europe/Berlin',
    current: [
        CurrentField::TEMPERATURE_2M,
        CurrentField::RAIN
    ],
    hourly: [
        HourlyField::WEATHER_CODE,
        HourlyField::TEMPERATURE_2M
    ]
);

$forecast = $client->forecast($request);
```