<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Enum;

/**
 * Possible fields/values for current weather information
 */
enum CurrentField: string
{
    use BackedEnumValuesTrait;

    case WEATHER_CODE = 'weathercode';
    case TEMPERATURE_2M = 'temperature_2m';
    case APPARENT_TEMPERATURE = 'apparent_temperature';
    case CLOUD_COVER = 'cloud_cover';
    case RELATIVE_HUMIDITY_2M = 'relative_humidity_2m';
    case VISIBILITY = 'visibility';
    case PRESSURE_MSL = 'pressure_msl';
    case SURFACE_PRESSURE = 'surface_pressure';
    case WIND_SPEED_10M = 'wind_speed_10m';
    case WIND_GUSTS_10M = 'wind_gusts_10m';
    case WIND_DIRECTION_10M = 'wind_direction_10m';
    case PRECIPITATION = 'precipitation';
    case PRECIPITATION_PROBABILITY = 'precipitation_probability';
    case RAIN = 'rain';
    case SHOWERS = 'showers';
    case SNOWFALL = 'snowfall';
    case SNOW_DEPTH = 'snow_depth';
    case UV_INDEX = 'uv_index';
    case IS_DAY = 'is_day';
    case DEW_POINT_2M = 'dew_point_2m';
}
