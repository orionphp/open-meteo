<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Enum;

/**
 * Enumeration representing WMO weather codes.
 * Open Meteo is not using the full range 0-99, but it's already covered.
 */
enum WeatherCode: int
{
    case ClearSky = 0;
    case PartlyCloudy = 1;
    case Cloudy = 2;
    case Overcast = 3;

    case Fog = 45;
    case FreezingFog = 48;

    case LightDrizzle = 51;
    case ModerateDrizzle = 53;
    case HeavyDrizzle = 55;
    case LightFreezingDrizzle = 56;
    case HeavyFreezingDrizzle = 57;

    case LightRain = 61;
    case ModerateRain = 63;
    case HeavyRain = 65;
    case LightFreezingRain = 66;
    case HeavyFreezingRain = 67;

    case LightSnow = 71;
    case ModerateSnow = 73;
    case HeavySnow = 75;
    case SnowGrains = 77;

    case LightRainShowers = 80;
    case ModerateRainShowers = 81;
    case HeavyRainShowers = 82;
    case LightSnowShowers = 85;
    case HeavySnowShowers = 86;

    case Thunderstorm = 95;
    case ThunderstormHailLight = 96;
    case ThunderstormHailHeavy = 99;

    //Not used by Open Meteo / undefined values
    case UndefinedPrecipitation = -10; // 4–44
    case UndefinedDrizzle = -11;       // 52,54
    case UndefinedRain = -12;          // 62,64
    case UndefinedSnow = -13;          // 72,74,76
    case UndefinedThunderstorm = -14;  // 97–98
    case Undefined = -1;               // Others

    /**
     * @param int $code
     * @return self
     */
    public static function fromInt(int $code): self
    {
        $tryFromResult = self::tryFrom($code);

        return $tryFromResult ?? match (true) {
            // Not defined, but groupable by logic

            $code >= 4 && $code <= 44 => self::UndefinedPrecipitation,

            $code === 52 || $code === 54 => self::UndefinedDrizzle,

            $code === 62 || $code === 64 => self::UndefinedRain,

            $code === 72 || $code === 74 || $code === 76 => self::UndefinedSnow,

            $code === 97 || $code === 98 => self::UndefinedThunderstorm,

            default => self::Undefined,
        };
    }

    /**
     * @return bool
     */
    public function isDefined(): bool
    {
        return $this->value >= 0;
    }

}
