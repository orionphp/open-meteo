<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Factory;

use function is_array;

use Orionphp\OpenMeteo\Parser\CurrentParser;
use Orionphp\OpenMeteo\Parser\DailyParser;
use Orionphp\OpenMeteo\Parser\HourlyParser;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use Orionphp\OpenMeteo\Response\Forecast;

final class ForecastFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public static function fromApiResponse(
        array           $data,
        ForecastRequest $request
    ): Forecast {

        /** @var array<string, mixed>|null $currentUnits */
        $currentUnits = is_array($data['current_units'] ?? null)
            ? $data['current_units']
            : null;

        /** @var array<string, mixed>|null $hourlyUnits */
        $hourlyUnits = is_array($data['hourly_units'] ?? null)
            ? $data['hourly_units']
            : null;

        /** @var array<string, mixed>|null $dailyUnits */
        $dailyUnits = is_array($data['daily_units'] ?? null)
            ? $data['daily_units']
            : null;

        /** @var array<string, mixed>|null $currentSection */
        $currentSection = is_array($data['current'] ?? null)
            ? $data['current']
            : null;

        /** @var array<string, mixed>|null $hourlySection */
        $hourlySection = is_array($data['hourly'] ?? null)
            ? $data['hourly']
            : null;

        /** @var array<string, mixed>|null $dailySection */
        $dailySection = is_array($data['daily'] ?? null)
            ? $data['daily']
            : null;

        return new Forecast(
            current: CurrentParser::parse(
                $currentSection,
                $currentUnits
            ),
            hourly: HourlyParser::parse(
                $hourlySection,
                $hourlyUnits,
                $request
            ),
            daily: DailyParser::parse(
                $dailySection,
                $dailyUnits,
                $request
            ),
        );
    }
}
