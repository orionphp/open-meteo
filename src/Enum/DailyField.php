<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Enum;

/**
 * Possible fields/values for daily weather forecast
 */
enum DailyField: string
{
    use BackedEnumValuesTrait;

    case WEATHER_CODE = 'weathercode';
    case TEMPERATURE_2M_MAX = 'temperature_2m_max';
    case TEMPERATURE_2M_MIN = 'temperature_2m_min';
    case APPARENT_TEMPERATURE_MAX = 'apparent_temperature_max';
    case APPARENT_TEMPERATURE_MIN = 'apparent_temperature_min';
    case PRECIPITATION_SUM = 'precipitation_sum';
    case PRECIPITATION_PROBABILITY_MAX = 'precipitation_probability_max';
    case RAIN_SUM = 'rain_sum';
    case SHOWERS_SUM = 'showers_sum';
    case SNOWFALL_SUM = 'snowfall_sum';
    case SNOW_DEPTH_MAX = 'snow_depth_max';
    case WIND_SPEED_10M_MAX = 'wind_speed_10m_max';
    case WIND_GUSTS_10M_MAX = 'wind_gusts_10m_max';
    case WIND_DIRECTION_10M_DOMINANT = 'wind_direction_10m_dominant';
    case SUNRISE = 'sunrise';
    case SUNSET = 'sunset';
    case SUNSHINE_DURATION = 'sunshine_duration';
    case UV_INDEX_MAX = 'uv_index_max';
    case SHORTWAVE_RADIATION_SUM = 'shortwave_radiation_sum';
    case ET0_FAO_EVAPOTRANSPIRATION = 'et0_fao_evapotranspiration';
}
