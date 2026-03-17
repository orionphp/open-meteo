<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Response\Current;

use Orionphp\OpenMeteo\Enum\CurrentField;

final readonly class CurrentData
{
    /**
     * @param string|null $time
     * @param array<string, CurrentFieldData> $fields
     */
    public function __construct(
        private ?string $time,
        private array   $fields,
    ) {
    }

    public function time(): ?string
    {
        return $this->time;
    }

    public function field(CurrentField $field): ?CurrentFieldData
    {
        return $this->fields[$field->value] ?? null;
    }

    /**
     * @return list<CurrentField>
     */
    public function availableFields(): array
    {
        return array_map(
            static fn (string $value) => CurrentField::from($value),
            array_keys($this->fields)
        );
    }
}
