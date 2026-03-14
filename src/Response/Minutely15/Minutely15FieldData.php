<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Response\Minutely15;

use Orionphp\OpenMeteo\Enum\Minutely15Field;
use Orionphp\OpenMeteo\Enum\WeatherModel;

final readonly class Minutely15FieldData
{
    /**
     * @param array<string, list<float|int|null>> $modelValues
     */
    public function __construct(
        private Minutely15Field $field,
        private ?string         $unit,
        private array           $modelValues,
    ) {
    }

    public function field(): Minutely15Field
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
