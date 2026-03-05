<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Http;

use BackedEnum;
use Orionphp\OpenMeteo\Enum\WeatherModel;
use Orionphp\OpenMeteo\Request\ForecastRequest;

/**
 * Builds the query parameters for the forecast request.
 */
final class ForecastQueryBuilder
{
    /**
     * @return array<string, string|float>
     */
    public static function build(ForecastRequest $request): array
    {
        $query = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'models' => WeatherModel::toQuery($request->models),
        ];

        if ($request->timezone !== null && $request->timezone !== '') {
            $query['timezone'] = $request->timezone;
        }

        if ($request->current !== null && $request->current !== []) {
            $query['current'] = self::implodeEnums($request->current);
        }

        if ($request->hourly !== null && $request->hourly !== []) {
            $query['hourly'] = self::implodeEnums($request->hourly);
        }

        if ($request->daily !== null && $request->daily !== []) {
            $query['daily'] = self::implodeEnums($request->daily);
        }

        return $query;
    }

    /**
     * @param list<BackedEnum> $enums
     */
    private static function implodeEnums(array $enums): string
    {
        return implode(
            ',',
            array_map(
                static fn(BackedEnum $e) => (string)$e->value,
                $enums
            )
        );
    }
}