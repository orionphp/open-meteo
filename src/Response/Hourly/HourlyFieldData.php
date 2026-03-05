<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Response\Hourly;

use Orionphp\OpenMeteo\Enum\HourlyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;

final readonly class HourlyFieldData
{
    /**
     * @param array<string, list<float|int|null>> $modelValues
     */
    public function __construct(
        private HourlyField $field,
        private ?string     $unit,
        private array       $modelValues,
    ) {
    }

    public function field(): HourlyField
    {
        return $this->field;
    }

    public function unit(): ?string
    {
        return $this->unit;
    }

    /**
     * @return list<float|int|null>
     */
    public function values(WeatherModel $model): array
    {
        return $this->modelValues[$model->value] ?? [];
    }

    /**
     * @return list<WeatherModel>
     */
    public function models(): array
    {
        return array_map(
            static fn (string $value) => WeatherModel::from($value),
            array_keys($this->modelValues)
        );
    }
}
