<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Parser;

use function array_map;

use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use Orionphp\OpenMeteo\Response\Hourly\HourlyData;

use Orionphp\OpenMeteo\Response\Hourly\HourlyFieldData;

final class HourlyParser
{
    /**
     * @param array<string, mixed>|null $section
     * @param array<string, mixed>|null $units
     */
    public static function parse(
        ?array $section,
        ?array $units,
        ForecastRequest $request
    ): ?HourlyData {

        if ($section === null) {
            return null;
        }

        [$time, $section] = TimeExtractor::extractList($section);

        $activeModels = array_map(
            static fn ($model) => $model->value,
            $request->models
        );

        /** @var array<string, HourlyFieldData> $fields */
        $fields = MultiModelParser::parse(
            $section,
            $units,
            $activeModels,
            HourlyField::class,
            static function (
                HourlyField $fieldEnum,
                ?string $unit,
                array $modelValues
            ): HourlyFieldData {

                /** @var array<string, list<float|int|null>> $narrowed */
                $narrowed = [];

                foreach ($modelValues as $model => $values) {
                    /** @var list<float|int|null> $values */
                    $narrowed[$model] = $values;
                }

                return new HourlyFieldData(
                    field: $fieldEnum,
                    unit: $unit,
                    modelValues: $narrowed
                );
            }
        );

        return new HourlyData(
            time: $time,
            fields: $fields
        );
    }
}
