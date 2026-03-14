<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Enum;

/**
 * Possible fields/values for hourly weather forecast
 */
enum HourlyField: string
{
    use BackedEnumValuesTrait;

    case WEATHER_CODE = 'weathercode';
    case TEMPERATURE_2M = 'temperature_2m';
    case APPARENT_TEMPERATURE = 'apparent_temperature';
    case SOIL_TEMPERATURE_0CM = 'soil_temperature_0cm';
    case SOIL_TEMPERATURE_6CM = 'soil_temperature_6cm';
    case SOIL_TEMPERATURE_18CM = 'soil_temperature_18cm';
    case SOIL_TEMPERATURE_54CM = 'soil_temperature_54cm';
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
    case DEW_POINT_2M = 'dew_point_2m';
    case SHORTWAVE_RADIATION = 'shortwave_radiation';
    case DIRECT_RADIATION = 'direct_radiation';
    case DIFFUSE_RADIATION = 'diffuse_radiation';
    case DIRECT_NORMAL_IRRADIANCE = 'direct_normal_irradiance';
    case EVAPOTRANSPIRATION = 'evapotranspiration';
    case ET0_FAO_EVAPOTRANSPIRATION = 'et0_fao_evapotranspiration';
    case VAPOUR_PRESSURE_DEFICIT = 'vapour_pressure_deficit';
    case CAPE = 'cape';
}
