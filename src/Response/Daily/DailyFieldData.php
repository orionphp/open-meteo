<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Response\Daily;

use Orionphp\OpenMeteo\Enum\DailyField;
use Orionphp\OpenMeteo\Enum\WeatherModel;

final readonly class DailyFieldData
{
    /**
     * @param array<string, list<float|int|string|null>> $modelValues
     */
    public function __construct(
        private DailyField $field,
        private ?string    $unit,
        private array      $modelValues,
    ) {
    }

    public function field(): DailyField
    {
        return $this->field;
    }

    public function unit(): ?string
    {
        return $this->unit;
    }

    /**
     * @return list<float|int|string|null>
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
