# Orionphp Open-Meteo Client

[![CI](https://github.com/ORGANIZATION/REPO/actions/workflows/ci.yml/badge.svg)](https://github.com/ORGANIZATION/REPO/actions)

[![Latest Stable Version](https://poser.pugx.org/orionphp/open-meteo/v)](https://packagist.org/packages/orionphp/open-meteo)

[![Total Downloads](https://poser.pugx.org/orionphp/open-meteo/downloads)](https://packagist.org/packages/orionphp/open-meteo)

[![PHP Version Require](https://poser.pugx.org/orionphp/open-meteo/require/php)](https://packagist.org/packages/orionphp/open-meteo)

[![License](https://poser.pugx.org/orionphp/open-meteo/license)](https://packagist.org/packages/orionphp/open-meteo)

[![Code Style](https://img.shields.io/badge/code%20style-php--cs--fixer-brightgreen)](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)

![PHPStan](https://img.shields.io/badge/PHPStan-level%20max-brightgreen)

[![Coverage](https://codecov.io/gh/orionphp/open-meteo/branch/main/graph/badge.svg)](https://codecov.io)


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

---


# Requirements

- PHP **8.4+**
- Composer
- PSR-18 HTTP client
- PSR-17 request factory
- PSR-7 HTTP message implementation

Optional:

- PSR-3 logger

If you don't already have these installed, a common setup is:

```bash
composer require guzzlehttp/guzzle nyholm/psr7
```

---

# Basic Usage with Guzzle and Nyholm\Psr7
(replace them with your preferred HTTP client and PSR-7 implementation)

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