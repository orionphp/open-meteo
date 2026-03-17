<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Response\Current;

use Orionphp\OpenMeteo\Enum\CurrentField;

final readonly class CurrentFieldData
{
    public function __construct(
        private CurrentField          $field,
        private ?string               $unit,
        private float|int|string|null $value,
    ) {
    }

    public function field(): CurrentField
    {
        return $this->field;
    }

    public function unit(): ?string
    {
        return $this->unit;
    }

    public function value(): float|int|string|null
    {
        return $this->value;
    }
}
