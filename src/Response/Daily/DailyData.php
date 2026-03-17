<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Response\Daily;

use Orionphp\OpenMeteo\Enum\DailyField;

final readonly class DailyData
{
    /**
     * @param list<string> $time
     * @param array<string, DailyFieldData> $fields
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

    public function field(DailyField $field): ?DailyFieldData
    {
        return $this->fields[$field->value] ?? null;
    }

    /**
     * @return list<DailyField>
     */
    public function availableFields(): array
    {
        return array_map(
            static fn (string $value) => DailyField::from($value),
            array_keys($this->fields)
        );
    }
}
