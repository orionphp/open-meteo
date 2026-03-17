<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Response\Hourly;

use Orionphp\OpenMeteo\Enum\HourlyField;

final readonly class HourlyData
{
    /**
     * @param list<string> $time
     * @param array<string, HourlyFieldData> $fields
     */
    public function __construct(
        private array $time,
        private array $fields,
    ) {
    }

    /**
     * @return list<string>
     */
    public function time(): array
    {
        return $this->time;
    }

    public function field(HourlyField $field): ?HourlyFieldData
    {
        return $this->fields[$field->value] ?? null;
    }

    /**
     * @return list<HourlyField>
     */
    public function availableFields(): array
    {
        return array_map(
            static fn (string $value) => HourlyField::from($value),
            array_keys($this->fields)
        );
    }
}
