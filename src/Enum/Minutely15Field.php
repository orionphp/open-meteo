<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Enum;

/**
 * Possible fields/values for 15-minute weather forecast
 */
enum Minutely15Field: string
{
    use BackedEnumValuesTrait;

    case TEMPERATURE_2M = 'temperature_2m';
    case RELATIVE_HUMIDITY_2M = 'relative_humidity_2m';
    case DEW_POINT_2M = 'dew_point_2m';
    case APPARENT_TEMPERATURE = 'apparent_temperature';
    case PRECIPITATION = 'precipitation';
    case RAIN = 'rain';
    case SNOWFALL = 'snowfall';
    case SHOWERS = 'showers';
    case WEATHER_CODE = 'weathercode';
    case CLOUD_COVER = 'cloud_cover';
    case WIND_SPEED_10M = 'wind_speed_10m';
    case WIND_DIRECTION_10M = 'wind_direction_10m';
    case WIND_GUSTS_10M = 'wind_gusts_10m';
    case VISIBILITY = 'visibility';
    case SHORTWAVE_RADIATION = 'shortwave_radiation';
    case DIRECT_RADIATION = 'direct_radiation';
    case DIFFUSE_RADIATION = 'diffuse_radiation';
    case DIRECT_NORMAL_IRRADIANCE = 'direct_normal_irradiance';
}
