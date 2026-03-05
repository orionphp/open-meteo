<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Response;

use Orionphp\OpenMeteo\Response\Current\CurrentData;
use Orionphp\OpenMeteo\Response\Daily\DailyData;
use Orionphp\OpenMeteo\Response\Hourly\HourlyData;

/**
 * Forecast response.
 */
final readonly class Forecast
{
    public function __construct(
        public ?CurrentData $current,
        public ?HourlyData  $hourly,
        public ?DailyData   $daily,
    ) {
    }

    public function hasCurrent(): bool
    {
        return $this->current !== null;
    }

    public function hasHourly(): bool
    {
        return $this->hourly !== null;
    }

    public function hasDaily(): bool
    {
        return $this->daily !== null;
    }
}
