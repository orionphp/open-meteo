<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Response\Minutely15;

use Orionphp\OpenMeteo\Enum\Minutely15Field;

final readonly class Minutely15Data
{
    /**
     * @param list<string> $time
     * @param array<string, Minutely15FieldData> $fields
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

    public function field(Minutely15Field $field): ?Minutely15FieldData
    {
        return $this->fields[$field->value] ?? null;
    }

    /**
     * @return list<Minutely15Field>
     */
    public function availableFields(): array
    {
        return array_map(
            static fn (string $value) => Minutely15Field::from($value),
            array_keys($this->fields)
        );
    }
}
