<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Parser;

use BackedEnum;

use function count;
use function is_array;
use function is_string;
use function strlen;

final class MultiModelParser
{
    /**
     * @template TFieldEnum of BackedEnum
     * @template TFieldData
     *
     * @param array<string, mixed> $section
     * @param array<string, mixed>|null $units
     * @param list<string> $activeModels
     * @param class-string<TFieldEnum> $enumClass
     * @param callable(
     *      TFieldEnum,
     *      ?string,
     *      array<string, list<float|int|string|null>>
     * ): TFieldData $fieldFactory
     *
     * @return array<string, TFieldData>
     */
    public static function parse(
        array    $section,
        ?array   $units,
        array    $activeModels,
        string   $enumClass,
        callable $fieldFactory
    ): array {

        $grouped = self::groupByModel($section, $activeModels);

        return self::buildFieldObjects(
            $grouped,
            $units,
            $activeModels,
            $enumClass,
            $fieldFactory
        );
    }

    /**
     * @param array<string, mixed> $section
     * @param list<string> $activeModels
     * @return array<string, array<string, list<float|int|string|null>>>
     */
    private static function groupByModel(
        array $section,
        array $activeModels
    ): array {

        $hasSingleModel = count($activeModels) === 1;

        /** @var array<string, array<string, list<float|int|string|null>>> $grouped */
        $grouped = [];

        foreach ($section as $key => $value) {

            if (!is_array($value)) {
                continue;
            }

            /** @var list<float|int|string|null> $value */

            $baseField = $key;
            $matched = false;

            foreach ($activeModels as $model) {
                $suffix = '_' . $model;

                if (str_ends_with($key, $suffix)) {
                    $baseField = substr($key, 0, -strlen($suffix));
                    $grouped[$baseField][$model] = $value;
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                if ($hasSingleModel) {
                    $grouped[$baseField][$activeModels[0]] = $value;
                } else {
                    foreach ($activeModels as $model) {
                        $grouped[$baseField][$model] = $value;
                    }
                }
            }
        }

        return $grouped;
    }

    /**
     * @template TFieldEnum of BackedEnum
     * @template TFieldData
     *
     * @param array<string, array<string, list<float|int|string|null>>> $grouped
     * @param array<string, mixed>|null $units
     * @param list<string> $activeModels
     * @param class-string<TFieldEnum> $enumClass
     * @param callable(
     *      TFieldEnum,
     *      ?string,
     *      array<string, list<float|int|string|null>>
     * ): TFieldData $fieldFactory
     *
     * @return array<string, TFieldData>
     */
    private static function buildFieldObjects(
        array    $grouped,
        ?array   $units,
        array    $activeModels,
        string   $enumClass,
        callable $fieldFactory
    ): array {

        /** @var array<string, TFieldData> $fieldObjects */
        $fieldObjects = [];

        foreach ($grouped as $fieldKey => $modelValues) {

            $fieldEnum = $enumClass::tryFrom($fieldKey);
            if ($fieldEnum === null) {
                continue;
            }

            $unit = self::resolveUnit($fieldKey, $units, $activeModels);

            $fieldObjects[$fieldKey] = $fieldFactory(
                $fieldEnum,
                $unit,
                $modelValues
            );
        }

        return $fieldObjects;
    }

    /**
     * @param array<string, mixed>|null $units
     * @param list<string> $activeModels
     */
    private static function resolveUnit(
        string $fieldKey,
        ?array $units,
        array  $activeModels
    ): ?string {

        if ($units === null) {
            return null;
        }

        foreach ($units as $unitKey => $unitValue) {

            if (!is_string($unitValue)) {
                continue;
            }

            $baseUnitKey = $unitKey;

            foreach ($activeModels as $model) {
                $suffix = '_' . $model;

                if (str_ends_with($unitKey, $suffix)) {
                    $baseUnitKey = substr($unitKey, 0, -strlen($suffix));
                    break;
                }
            }

            if ($baseUnitKey === $fieldKey) {
                return $unitValue;
            }
        }

        return null;
    }
}
