<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Parser;

use function array_map;

use Orionphp\OpenMeteo\Enum\Minutely15Field;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use Orionphp\OpenMeteo\Response\Minutely15\Minutely15Data;
use Orionphp\OpenMeteo\Response\Minutely15\Minutely15FieldData;

final class Minutely15Parser
{
    /**
     * @param array<string, mixed>|null $section
     * @param array<string, mixed>|null $units
     */
    public static function parse(
        ?array          $section,
        ?array          $units,
        ForecastRequest $request
    ): ?Minutely15Data {

        if ($section === null) {
            return null;
        }

        [$time, $section] = TimeExtractor::extractList($section);

        $activeModels = array_map(
            static fn ($model) => $model->value,
            $request->models
        );

        /** @var array<string, Minutely15FieldData> $fields */
        $fields = MultiModelParser::parse(
            $section,
            $units,
            $activeModels,
            Minutely15Field::class,
            static function (
                Minutely15Field $fieldEnum,
                ?string         $unit,
                array           $modelValues
            ): Minutely15FieldData {

                /** @var array<string, list<float|int|null>> $narrowed */
                $narrowed = [];

                foreach ($modelValues as $model => $values) {
                    /** @var list<float|int|null> $values */
                    $narrowed[$model] = $values;
                }

                return new Minutely15FieldData(
                    field: $fieldEnum,
                    unit: $unit,
                    modelValues: $narrowed
                );
            }
        );

        return new Minutely15Data(
            time: $time,
            fields: $fields
        );
    }
}
