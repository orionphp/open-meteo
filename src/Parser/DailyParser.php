<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Parser;

use function array_map;

use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use Orionphp\OpenMeteo\Response\Daily\DailyData;

use Orionphp\OpenMeteo\Response\Daily\DailyFieldData;

final class DailyParser
{
    /**
     * @param array<string, mixed>|null $section
     * @param array<string, mixed>|null $units
     */
    public static function parse(
        ?array $section,
        ?array $units,
        ForecastRequest $request
    ): ?DailyData {

        if ($section === null) {
            return null;
        }

        [$time, $section] = TimeExtractor::extractList($section);

        $activeModels = array_map(
            static fn ($model) => $model->value,
            $request->models
        );

        /** @var array<string, DailyFieldData> $fields */
        $fields = MultiModelParser::parse(
            $section,
            $units,
            $activeModels,
            DailyField::class,
            static fn (
                DailyField $fieldEnum,
                ?string $unit,
                array $modelValues
            ): DailyFieldData => new DailyFieldData(
                field: $fieldEnum,
                unit: $unit,
                modelValues: $modelValues
            )
        );

        return new DailyData(
            time: $time,
            fields: $fields
        );
    }
}
